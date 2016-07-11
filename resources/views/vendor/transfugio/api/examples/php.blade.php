$client = new GuzzleHttp\Client;
$response = $client->get("{{ $url }}");
var_export($response->json());