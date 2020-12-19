<?php

namespace SearChili\Api;

use SearChili\Exception\RequestException;
use SearChili\Model\Entity as EntityModel;

class Entity extends Api
{
    /**
     * @param string $query
     * @param int $size
     * @param int $from
     * @return EntityModel[]
     * @throws RequestException
     */
    public function search($query, $size = 15, $from = 0)
    {
        $response = $this->client->get(
            'entity/search',
            [
                'q' => $query,
                'size' => $size,
                'from' => $from,
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
                'q' => $query,
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

    /**
     * @param $id
     * @param $title
     * @param $link
     * @param null $excerpt
     * @param null $body
     * @param null $image
     * @param null $categories
     * @param null $tags
     * @param null $published_at
     * @return bool
     * @throws RequestException
     */
    public function store(
        $id,
        $title,
        $link,
        $excerpt = null,
        $body = null,
        $image = null,
        $categories = null,
        $tags = null,
        $published_at = null
    ) {
        $response = $this->client->put(
            'entity/' . $id,
            [
                'id' => $id,
                'title' => $title,
                'link' => $link,
                'excerpt' => $excerpt,
                'body' => $body,
                'image' => $image,
                'categories' => $categories,
                'tags' => $tags,
                'published_at' => $published_at,
            ]
        );
        if ($response->getStatusCode() == 201) {
            return true;
        }
        throw new RequestException("Failed to store this entity, response body:\n" . json_encode($response->getContent()));
    }

    /**
     * @param $id
     * @return bool
     * @throws RequestException
     */
    public function delete($id)
    {
        $response = $this->client->delete('entity/' . $id);
        if ($response->getStatusCode() == 200) {
            return true;
        }
        throw new RequestException("Failed to delete this entity, response body:\n" . json_encode($response->getContent()));
    }
}
