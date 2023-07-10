<?php

namespace albawebstudio\PetfinderApi;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Animal extends APIClient
{
    /**
     * @param array $params
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function animals(array $params, string $sort = 'random', int $page = 0, int $limit = 0): ResponseInterface
    {
        return $this->get('animals', $params, $sort, $page, $limit);
    }

    /**
     * @param int $animalId
     * @param array $params
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function animal(int $animalId, array $params): ResponseInterface
    {
        return $this->get("animals/$animalId", $params);
    }

}