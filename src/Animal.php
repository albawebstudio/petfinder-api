<?php

namespace albawebstudio\PetfinderApi;

use albawebstudio\PetfinderApi\exceptions\InvalidAuthorizationException;
use albawebstudio\PetfinderApi\exceptions\InvalidRequestException;
use albawebstudio\PetfinderApi\exceptions\PetfinderConnectorException;

class Animal extends PetfinderBaseComponent
{
    /**
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

}
