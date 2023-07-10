<?php

namespace albawebstudio\PetfinderApi;

use albawebstudio\PetfinderApi\exceptions\InvalidAuthorizationException;
use albawebstudio\PetfinderApi\exceptions\InvalidRequestException;
use albawebstudio\PetfinderApi\exceptions\PetfinderConnectorException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

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

    /**
     * @param string $route
     * @param array $params
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @param bool $appendOrganization
     * @return mixed
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     * @throws GuzzleException
     */
    protected function get(string $route, array $params = [], string $sort = '', int $page = 0, int $limit = 50, bool $appendOrganization = true): mixed
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

        return $this->_connector->api($route, $params, PetfinderConnector::GET, null, $appendOrganization);
    }
}
