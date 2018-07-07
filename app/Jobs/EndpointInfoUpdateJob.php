<?php

namespace App\Jobs;

use App\Model\Endpoint;
use App\Model\EndpointBody;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EndpointInfoUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            $endpoint = Endpoint::query()->firstOrCreate([
                'url'   => $this->endpointData['url'],
                'title' => $this->endpointData['title'],
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

        $this->getBodiesFromEndpoint($log, $endpoint);

        $endpoint->endpoint_fetched = Carbon::now();

        $endpoint->save();
    }

    /**
     * @param Log $log
     * @param     $endpoint
     */
    protected function getBodiesFromEndpoint(Log $log, $endpoint)
    {
        $systemJson = $this->getSystemFromEndpoint($endpoint);

        if (is_null($systemJson)) {
            $log->warning("System check for '{$endpoint->url}' failed");
            // TODO: slack notification
            $this->fail();

            return;
        }

        $guzzle = new Client();
        $bodyResponse = $guzzle->get($systemJson['body']);
        $bodyJson = json_decode((string)$bodyResponse->getBody(), true);

        if (!array_key_exists('data', $bodyJson)) {
            $log->error("Endpoint {$endpoint->url} does not appear to have a valid body list.", $bodyJson);
            $this->fail();

            return;
        }

        collect($bodyJson['data'])->each(function (array $body) use ($log, $endpoint) {
            /** @var EndpointBody $endpointBody */
            $endpointBody = EndpointBody::query()->firstOrCreate([
                'endpoint_id' => $endpoint->id,
                'oparl_id'    => $body['id'],
            ]);

            try {
                $endpointBody->name = $body['name'];
            } catch (\ErrorException $e) {
                $log->error("Body {$body['id']} does not seem to have a name, this is bad!", $body);
            }

            if (array_key_exists('website', $body)) {
                $endpointBody->website = $body['website'];
            }

            $endpointBody->endpoint()->associate($endpoint);

            $endpointBody->save();
        });
    }

    /**
     * @param $endpoint
     * @return array
     */
    protected function getSystemFromEndpoint($endpoint)
    {
        $systemJson = null;

        try {
            $guzzle = new Client();
            $systemResponse = $guzzle->get($endpoint->url);
            $systemJson = json_decode((string)$systemResponse->getBody(), true);
            $endpoint->system = $systemJson;
        } catch (RequestException $e) {
        }

        return $systemJson;
    }
}
