<?php

namespace albawebstudio\PetfinderApi;

use albawebstudio\PetfinderApi\exceptions\InvalidAuthorizationException;
use albawebstudio\PetfinderApi\exceptions\InvalidRequestException;
use albawebstudio\PetfinderApi\exceptions\PetfinderConnectorException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PetfinderConnector
{
    const GET = 'GET';
    const POST = 'POST';

    /**
     * @var string
     */
    static string $key;

    /**
     * @var string
     */
    static string $secret;

    /**
     * @var string
     */
    static string $organization;

    /**
     * @var string
     */
    static string $baseUrl = 'https://api.petfinder.com';

    /**
     * @var string
     */
    static string $version = 'v2';

    /**
     * @var string|null
     */
    static ?string $accessToken;

    /**
     * @var HandlerStack
     */
    static HandlerStack $handlerStack;

    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @param string $key
     * @param string $secret
     * @param string $organization
     * @param ?HandlerStack $handlerStack
     * @return void
     */
    public static function init(
        string $key,
        string $secret,
        string $organization,
        HandlerStack $handlerStack = null
    ): void
    {
        self::$key = $key;
        self::$secret = $secret;
        self::$organization = $organization;

        self::$accessToken = Cache::get('petfinder_access_token', null);

        if (null !== $handlerStack) {
            self::$handlerStack     = $handlerStack;
        }

        self::authenticate();

        self::$instance = null;
    }

    /**
     * @var PetfinderConnector|null
     */
    private static ?PetfinderConnector $instance = null;

    /**
     * @return PetfinderConnector|null
     * @throws Exception
     */
    public static function getInstance(): ?PetfinderConnector
    {
        if (self::$instance == null) {
            self::$instance = new self();
            self::$instance->getClient();
        }
        return self::$instance;
    }

    public static function authenticate(): void
    {
        try {
            $headers = [
                'User-Agent' => 'albawebstudio-petfinderapi/1.0',
                'Accept' => 'application/json',
            ];
            $data = [
                'grant_type' => 'client_credentials',
                'client_id' => self::$key,
                'client_secret' => self::$secret,
            ];

            $config = [
                'base_uri' => self::getEndpointUrl(),
            ];

            $client = new Client($config);

            $response = $client->request(self::POST, "oauth2/token", [
                'headers' => $headers,
                'json' => $data,
            ]);

            // TODO: CACHE ACCESS TOKEN
            self::$accessToken = json_decode($response->getBody()->getContents(), true)['access_token'];
            Cache::add('petfinder_access_token', self::$accessToken, Carbon::now()->addHour());

        } catch (ClientException | GuzzleException $exception) {
            $logger = new Log();
            $logger->error($exception->getMessage());
        }
    }

    /**
     * @return bool
     */
    public static function isAuthenticated(): bool
    {
        return ! is_null(self::$accessToken);
    }

    /**
     * @return Client
     * @throws Exception
     */
    private function getClient(): Client
    {
        if (!isset($this->client)) {

            if (!isset(self::$accessToken)) {
                throw new Exception('Invalid access token');
            }

            $config = [
                'base_uri' => self::getEndpointUrl(),
            ];

            if (isset(self::$handlerStack)) {
                $config['handler'] = self::$handlerStack;
            }

            $this->client = new Client($config);
        }
        return $this->client;
    }

    /**
     * @param $resource
     * @param array $params
     * @param string $method
     * @param $body
     * @param bool $onlyResource
     * @param bool $returnDespiteError
     * @return mixed|void
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function api($resource, array $params = [], string $method = "GET", $body = null, bool $onlyResource = false, bool $returnDespiteError = false)
    {

        try {
            $client = self::getClient();

            $headers = [
                'User-Agent'    => 'albawebstudio-petfinderapi/1.0',
                'Accept'        => 'application/json',
            ];

            if (null !== self::$accessToken) {
                $headers['Authorization'] = 'Bearer ' . self::$accessToken;
            }

            if ($body !== null) {
                $headers['content-type'] = 'application/json';
            }

            $response = $client->request($method, $this->getEndpointUrl() . "$resource", [
                'headers' => $headers,
                'query' => array_merge([ 'organization' => self::$organization ], $params),
                'body' => $body,
            ]);

            if ($response->getStatusCode() >= 300) {
                throw new PetfinderConnectorException($response->getBody(), $response->getStatusCode());
            }

            $data = json_decode($response->getBody(), true);

            return $onlyResource ? $data['animals'] : $data;

        } catch (RequestException | GuzzleException $e) {
            if ($responseBody = $e->getResponse()->getBody()) {
                $data = json_decode($responseBody, true);

                if($returnDespiteError) {
                    return $onlyResource ? $data['resource'] :$data;
                }

                switch ($data['status']) {
                    case 401:
                        throw new InvalidAuthorizationException($data['message'], $data['status']);
                    case 400:
                        throw new InvalidRequestException($data['message'], $data['status']);
                }
                throw new PetfinderConnectorException($data['message'], $data['status']);
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @return string
     */
    protected static function getEndpointUrl(): string
    {
        return self::$baseUrl . '/' . self::$version . '/';
    }
}
