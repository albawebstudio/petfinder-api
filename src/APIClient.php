<?php

namespace albawebstudio\PetfinderApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class APIClient
{
    const GET = 'GET';
    const POST = 'POST';

    /**
     * @var string
     */
    protected string $key;

    /**
     * @var string
     */
    protected string $secret;

    /**
     * @var string
     */
    protected string $organization;

    private Client $client;

    /**
     * @var string
     */
    protected string $baseUrl = 'https://api.petfinder.com';

    /**
     * @var string
     */
    protected string $version = 'v2';

    /**
     * @var string|null
     */
    protected ?string $accessToken = null;

    public function __construct(string $key, string $secret, string $organization)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->organization = $organization;
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'defaults' => [
                'query' => [
                    'organization' => $this->organization,
                ],
            ],
        ]);
    }

    /**
     * @return void
     */
    private function authenticate(): void
    {
        try {
            $headers = [
                'User-Agent' => 'albawebstudio-petfinderapi/1.0',
                'Accept' => 'application/json',
            ];
            $data = [
                'grant_type' => 'client_credentials',
                'client_id' => $this->key,
                'client_secret' => $this->secret,
            ];
            $response = $this->client->request(self::POST, "$this->baseUrl/$this->version/oauth2/token", [
                'headers' => $headers,
                'json' => $data,
            ]);

            // TODO: CACHE ACCESS TOKEN
            $this->accessToken = json_decode($response->getBody()->getContents(), true)['access_token'];

        } catch (ClientException | GuzzleException $exception) {

        }
    }

    /**
     * @return bool
     */
    protected function isAuthenticated(): bool
    {
        return ! is_null($this->accessToken);
    }

    /**
     * @param string $route
     * @param array $params
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return ResponseInterface
     * @throws GuzzleException
     */
    protected function get(string $route, array $params, string $sort = '', int $page = 0, int $limit = 50): ResponseInterface
    {
        if (!$this->isAuthenticated()) {
            $this->authenticate();
        }

        $headers = [
            'User-Agent' => 'albawebstudio-petfinderapi/1.0',
            'Authorization' => "Bearer $this->accessToken",
            'Accept' => 'application/json',
        ];

        if ($page > 0) {
            $limit = max(1, min($limit,100));
            $page = max(0, $page);
            $params['offset'] = $page * $limit;
            $params['limit'] = $limit;
        }

        if (!empty($sort)) {
            $params['sort'] = $sort;
        }

        return $this->client->request(self::GET, "$this->baseUrl/$this->version/$route", [
            'headers' => $headers,
            'query' => $params
        ]);
    }
}