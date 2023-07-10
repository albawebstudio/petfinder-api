<?php

namespace albawebstudio\PetfinderApi;

use albawebstudio\PetfinderApi\exceptions\InvalidAuthorizationException;
use albawebstudio\PetfinderApi\exceptions\InvalidRequestException;
use albawebstudio\PetfinderApi\exceptions\PetfinderConnectorException;
use GuzzleHttp\Exception\GuzzleException;

class Organization extends PetfinderBaseComponent
{
    /**
     * Fetch organizations from Petfinder.
     * See [query parameters](https://www.petfinder.com/developers/v2/docs/#get-organizations)
     *
     * @param array $params
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return array
     * @throws GuzzleException
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function organizations(array $params, string $sort = '', int $page = 0, int $limit = 0): array
    {
        return $this->get('organizations', $params, $sort, $page, $limit);
    }

    /**
     * Fetch specific organization by ID from Petfinder
     *
     * @param string $organizationId
     * @return array
     * @throws GuzzleException
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function organization(string $organizationId): array
    {
        return $this->get("organizations/$organizationId");
    }
}