<?php

namespace App\Jobs;

use App\Model\Endpoint;
use App\Model\EndpointBody;
use App\Notifications\SpecificationUpdateNotification;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;

class EndpointInfoUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use Notifiable;

    const CONNECT_TIMEOUT = 5;
    const RECEIVE_TIMEOUT = 2;
    protected $endpointData = [];

    /**
     * Create a new job instance.
     *
     * @param array $endpointData
     */
    public function __construct(array $endpointData)
    {
        $this->endpointData = $endpointData;
    }

    /**
     * Execute the job.
     *
     * @param LoggerInterface $log
     *
     * @return void
     */
    public function handle(LoggerInterface $log)
    {
        /** @var Endpoint $endpoint */
        $endpoint = null;

        try {
            $endpoint = Endpoint::firstOrNew(
                [
                    'url' => $this->endpointData['url'],
                ]
            );
        } catch (QueryException $e) {
            $log->error('Failed to update endpoint info', $this->endpointData);
            $log->error($e->getMessage(), $e->getTrace());

            return;
        }

        // update base data
        if (array_key_exists('title', $this->endpointData)) {
            $endpoint->title = $this->endpointData['title'];
        }

        if (array_key_exists('description', $this->endpointData)) {
            $endpoint->description = $this->endpointData['description'];
        }

        if (array_key_exists('osm', $this->endpointData)) {
            $endpoint->osm = (int)$this->endpointData['osm'];
        }

        if (array_key_exists('wd', $this->endpointData)) {
            $endpoint->wikidata = $this->endpointData['wd'];
        }

        $this->getSystemFromEndpoint($log, $endpoint);

        if (!$endpoint->exists) {
            // make sure to save a newly created endpoint before continuing
            $endpoint->endpoint_fetched = Carbon::now();
            $endpoint->save();
        }

        if (!is_array($endpoint->system)) {
            $this->getBodiesFromEndpoint($log, $endpoint);
        }

        $endpoint->endpoint_fetched = Carbon::now();
        $endpoint->save();
    }

    /**
     * @param $endpoint
     */
    protected function getSystemFromEndpoint(LoggerInterface $log, Endpoint $endpoint)
    {
        try {
            $guzzle = new Client();

            $systemResponse = $guzzle->get(
                $endpoint->url,
                [
                    // fail relatively fast on unreachable systems
                    'connect_timeout' => self::CONNECT_TIMEOUT,
                    'timeout'         => self::RECEIVE_TIMEOUT,
                ]
            );

            $systemJson = json_decode((string)$systemResponse->getBody(), true);

            if (!is_array($systemJson)) {
                $endpoint->system = [];

                $this->fail();

                return;
            }

            $endpoint->system = $systemJson;
        } catch (RequestException $e) {
            $log->warning('Failed to fetch system for '.$endpoint->url);
            $this->notify(
                SpecificationUpdateNotification::endpointInfoUpdateFailedNotification(
                    $endpoint->url,
                    $e->getMessage()
                )
            );

            // set system to empty json object in case non could be fetched
            $endpoint->system = [];

            $this->fail();
        }
    }

    /**
     * @param LoggerInterface $log
     * @param Endpoint        $endpoint
     */
    protected function getBodiesFromEndpoint(LoggerInterface $log, Endpoint $endpoint)
    {
        $systemJson = $endpoint->system;

        if (!array_key_exists('body', $systemJson)) {
            $log->debug('System '.$endpoint->url.' is missing body list');

            return;
        }

        try {
            $guzzle = new Client();

            $bodyResponse = $guzzle->get(
                $systemJson['body'],
                [
                    // fail relatively fast on unreachable systems
                    'connect_timeout' => self::CONNECT_TIMEOUT,
                    'timeout'         => self::RECEIVE_TIMEOUT,
                ]
            );

            $bodyJson = json_decode((string)$bodyResponse->getBody(), true);
        } catch (RequestException $e) {
            $log->debug('Failed to fetch body for system '.$systemJson['id']);
            $this->fail($e);

            return;
        }

        /** @var Validator $validator */
        $validator = \Validator::make(
            $bodyJson,
            [
                'data'           => 'required|array',
                'data.*.id'      => 'required|url',
                'data.*.name'    => 'string',
                'data.*.website' => 'string',
                'data.*.license' => 'string',
            ]
        );

        if ($validator->fails()) {
            $log->debug('Validation failed for Body list of '.$endpoint->url, $validator->errors()->all());
            $this->fail();

            return;
        }

        collect($bodyJson['data'])->each(
            function (array $body) use ($log, $endpoint) {
                /** @var EndpointBody $endpointBody */
                try {
                    $endpointBody = EndpointBody::whereEndpointId($endpoint->getKey())->whereOparlId(
                        $body['id']
                    )->firstOrFail();
                } catch (ModelNotFoundException $e) {
                    $endpointBody = new EndpointBody(
                        [
                            'name'     => (array_key_exists('name', $body)) ? $body['name'] : $body['id'],
                            // If there is no name, the id is at least another string uniquely identifying the body
                            'oparl_id' => $body['id'],
                        ]
                    );

                    $endpointBody->endpoint()->associate($endpoint);
                }

                $endpointBody->website = (array_key_exists('website', $body)) ? $body['website'] : '';
                $endpointBody->license = (array_key_exists('license', $body)) ? $body['license'] : '';
                $endpointBody->json = $body;

                $endpointBody->touch();
                $endpointBody->save();
            }
        );
    }

    public function routeNotificationsForSlack()
    {
        return config('services.slack.ci.endpoint');
    }
}
