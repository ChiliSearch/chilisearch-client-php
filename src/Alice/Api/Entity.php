<?php

namespace SearChili\Alice\Api;

use SearChili\Api;
use SearChili\Exception\RequestException;
use SearChili\Model\Entity as EntityModel;

class Entity extends Api
{
    /**
     * @param string $query
     * @param int $size
     * @param int $page
     * @return EntityModel[]
     * @throws RequestException
     */
    public function search($query, $size = 15, $page = 1)
    {
        $response = $this->client->get(
            'entity/search',
            [
                'query' => $query,
                'size' => $size,
                'page' => $page,
            ]
        );
        if ($response->getStatusCode() != 200) {
            throw new RequestException(sprintf(
                "Failed to execute search with error code (%d) and body:\n%s",
                $response->getStatusCode(), json_encode($response->getContent())
            ), $response->getStatusCode());
        }

        $entities = [];
        foreach ($response->getContent() as $entity) {
            $entities[] = new EntityModel($entity);
        }
        return $entities;
    }

    /**
     * @param string $query
     * @param int $size
     * @return EntityModel[]
     * @throws RequestException
     */
    public function sayt($query, $size = 5)
    {
        $response = $this->client->get(
            'entity/sayt',
            [
                'query' => $query,
                'size' => $size,
            ]
        );
        if ($response->getStatusCode() != 200) {
            throw new RequestException(sprintf(
                "Failed to execute search with error code (%d) and body:\n%s",
                $response->getStatusCode(), json_encode($response->getContent())
            ), $response->getStatusCode());
        }

        $entities = [];
        foreach ($response->getContent() as $entity) {
            $entities[] = new EntityModel($entity);
        }
        return $entities;
    }
}
