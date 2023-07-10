<?php

namespace albawebstudio\PetfinderApi;

use albawebstudio\PetfinderApi\exceptions\InvalidAuthorizationException;
use albawebstudio\PetfinderApi\exceptions\InvalidRequestException;
use albawebstudio\PetfinderApi\exceptions\PetfinderConnectorException;
use Exception;

class PetfinderBaseComponent
{
    /**
     * @var PetfinderConnector
     */
    protected PetfinderConnector $_connector;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->_connector = PetfinderConnector::getInstance();
    }

    /*private function authenticate(): void
    {
        try {
            $headers = [
                'User-Agent' => 'albawebstudio-petfinderapi/1.0',
                'Accept' => 'application/json',
            ];
            $data = [
                'grant_type' => 'client_credentials',
                'client_id' => $this->_connector::$key,
                'client_secret' => $this->_connector::$secret,
            ];
            $response = $this->_connector->api("oauth2/token", [
                    'headers' => $headers,
                    'json' => $data,
                ],
                PetfinderConnector::POST,
            );

            // TODO: CACHE ACCESS TOKEN
            $this->_connector::$accessToken = json_decode($response->getBody()->getContents(), true)['access_token'];
            Cache::add('petfinder_access_token', $this->_connector::$accessToken, Carbon::now()->addHour());

        } catch (InvalidAuthorizationException | InvalidRequestException | PetfinderConnectorException $exception) {
            $logger = new Log();
            $logger->error($exception->getMessage());
        }
    }*/

    /**
     * @return bool
     */
    /*public function isAuthenticated(): bool
    {
        return ! is_null(PetfinderConnector::$accessToken);
    }*/

    /**
     * @param string $route
     * @param array $params
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return mixed|null
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    protected function get(string $route, array $params = [], string $sort = '', int $page = 0, int $limit = 50): mixed
    {
        if (!PetfinderConnector::isAuthenticated()) {
            PetfinderConnector::authenticate();
        }

        if ($page > 0) {
            $limit = max(1, min($limit,100));
            $page = max(0, $page);
            $params['offset'] = $page * $limit;
            $params['limit'] = $limit;
        }

        if (!empty($sort)) {
            $params['sort'] = $sort;
        }

        return $this->_connector->api($route, $params, PetfinderConnector::GET);
    }
}
