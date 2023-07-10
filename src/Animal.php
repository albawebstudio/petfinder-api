<?php

namespace albawebstudio\PetfinderApi;

use albawebstudio\PetfinderApi\exceptions\InvalidAuthorizationException;
use albawebstudio\PetfinderApi\exceptions\InvalidRequestException;
use albawebstudio\PetfinderApi\exceptions\PetfinderConnectorException;

class Animal extends PetfinderBaseComponent
{
    /**
     * Fetch animals from Petfinder.
     * See [query parameters](https://www.petfinder.com/developers/v2/docs/#get-animals)
     *
     * @param array $params
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return array
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function animals(array $params, string $sort = 'random', int $page = 0, int $limit = 0): array
    {
        return $this->get('animals', $params, $sort, $page, $limit);
    }

    /**
     * Fetch specific animal by ID from Petfinder
     *
     * @param int $animalId
     * @param array $params
     * @return array
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function animal(int $animalId, array $params = []): array
    {
        return $this->get("animals/$animalId", $params);
    }

    /**
     * Fetch all Petfinder animal types
     *
     * @return array
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function types(): array
    {
        return $this->get('types');
    }

    /**
     * Fetch specific animal type by type from Petfinder
     *
     * @param string $type
     * @return array
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function type(string $type): array
    {
        return $this->get("types/$type");
    }

    /**
     * Fetch animal breeds by type from Petfinder
     *
     * @param string $type
     * @return array
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function breeds(string $type): array
    {
        return $this->get("types/$type/breeds");
    }

}
