<?php

namespace App\Jobs;

use App\Model\Endpoint;
use App\Model\EndpointBody;
use App\Notifications\SpecificationUpdateNotification;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EndpointInfoUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use Notifiable;

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
     * @param Log $log
     *
     * @return void
     */
    public function handle(Log $log)
    {
        /** @var Endpoint $endpoint */
        $endpoint = null;

        try {
            $endpoint = Endpoint::firstOrNew([
                'url' => $this->endpointData['url'],
            ]);
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

        $this->getSystemFromEndpoint($log, $endpoint);

        if (!$endpoint->exists) {
            // make sure to save a newly endpoint before continuing
            $endpoint->endpoint_fetched = Carbon::now();
            $endpoint->save();
        }

        $this->getBodiesFromEndpoint($log, $endpoint);

        $endpoint->endpoint_fetched = Carbon::now();
        $endpoint->save();
    }

    /**
     * @param $endpoint
     */
    protected function getSystemFromEndpoint(Log $log, Endpoint $endpoint)
    {
        try {
            $guzzle = new Client();

            $systemResponse = $guzzle->get($endpoint->url, [
                // fail relatively fast on unreachable systems
                'connect_timeout' => 5,
                'timeout' => 2
            ]);

            $systemJson = json_decode((string)$systemResponse->getBody(), true);
            $endpoint->system = $systemJson;
        } catch (RequestException $e) {
            $log->warning('Failed to fetch system for ' . $endpoint->url);
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
     * @param Log      $log
     * @param Endpoint $endpoint
     */
    protected function getBodiesFromEndpoint(Log $log, Endpoint $endpoint)
    {
        $systemJson = $endpoint->system;

        $guzzle = new Client();

        try {
            $bodyResponse = $guzzle->get($systemJson['body']);
            $bodyJson = json_decode((string)$bodyResponse->getBody(), true);

            \Validator::make($bodyJson, [
                'data'         => 'required|array',
                'data.*.id'      => 'required|url',
                'data.*.name'    => 'required|string',
                'data.*.website' => 'string',
                'data.*.license' => 'string',
            ])->validate();
        } catch (\Exception $e) {
            $this->fail($e);

            return;
        }

        collect($bodyJson['data'])->each(function (array $body) use ($log, $endpoint) {
            /** @var EndpointBody $endpointBody */
            try {
                $endpointBody = EndpointBody::whereEndpointId($endpoint->getKey())->whereOparlId($body['id'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                $endpointBody = new EndpointBody([
                    'name'     => $body['name'],
                    'oparl_id' => $body['id'],
                ]);

                $endpointBody->endpoint()->associate($endpoint);
            }

            $endpointBody->website = (array_key_exists('website', $body)) ? $body['website'] : '';
            $endpointBody->license = (array_key_exists('license', $body)) ? $body['license'] : '';
            $endpointBody->json = $body;

            $endpointBody->touch();
            $endpointBody->save();
        });
    }

    public function routeNotificationsForSlack()
    {
        return config('services.slack.ci.endpoint');
    }
}
