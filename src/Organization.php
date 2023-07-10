<?php

namespace albawebstudio\PetfinderApi;

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
     * @throws exceptions\InvalidAuthorizationException
     * @throws exceptions\InvalidRequestException
     * @throws exceptions\PetfinderConnectorException
     */
    public function organizations(array $params, string $sort = '', int $page = 0, int $limit = 0): array
    {
        return $this->get('organizations', $params, $sort, $page, $limit);
    }

    /**
     * Fetch specific organization by ID from Petfinder
     *
     * @param int $organizationId
     * @return array
     * @throws exceptions\InvalidAuthorizationException
     * @throws exceptions\InvalidRequestException
     * @throws exceptions\PetfinderConnectorException
     */
    public function organization(int $organizationId): array
    {
        return $this->get("organizations/$organizationId");
    }
}