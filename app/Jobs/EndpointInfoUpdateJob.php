<?php

namespace App\Jobs;

use App\Model\Endpoint;
use App\Model\EndpointBody;
use Carbon\Carbon;
use GuzzleHttp\Client;
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
                'url' => $this->endpointData['url'],
            ]);
        } catch (QueryException $e) {
            $log->error('Failed to update endpoint info', $this->endpointData);

            return;
        }

        // update base data
        if (array_key_exists('title', $this->endpointData)) {
            $endpoint->title = $this->endpointData['title'];
        }

        if (array_key_exists('description', $this->endpointData)) {
            $endpoint->description = $this->endpointData['description'];
        }

        // get the endpoint's system
        $guzzle = new Client();
        $systemResponse = $guzzle->get($endpoint->url);
        $systemJson = json_decode((string) $systemResponse->getBody(), true);
        $endpoint->system = $systemJson;

        // get the endpoint's body list and update the known endpoint bodies
        $bodyResponse = $guzzle->get($systemJson['body']);
        $bodyJson = json_decode((string) $bodyResponse->getBody(), true);

        if (!array_key_exists('data', $bodyJson)) {
            $endpoint->save();
            $log->error("Endpoint {$endpoint->url} does not appear to have a valid body list.", $bodyJson);
            $this->fail();
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

        $endpoint->endpoint_fetched = Carbon::now();

        $endpoint->save();
    }
}
