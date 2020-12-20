<?php

namespace SearChili\Bob\Api;

use SearChili\Api;
use SearChili\Exception\RequestException;
use SearChili\Model\Entity as EntityModel;

class Entity extends Api
{
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
     * @return EntityModel
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
    )
    {
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
            return new EntityModel($response->getContent());
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

    /**
     * @param $id
     * @return EntityModel
     * @throws RequestException
     */
    public function get($id)
    {
        $response = $this->client->get('entity/' . $id);
        if ($response->getStatusCode() == 200) {
            return new EntityModel($response->getContent());
        }
        throw new RequestException("Failed to get entity, response body:\n" . json_encode($response->getContent()));
    }

    /**
     * @return string[]
     * @throws RequestException
     */
    public function getAll($page = 1)
    {
        $response = $this->client->get(
            'entity',
            [
                'page' => $page,
            ]
        );
        if ($response->getStatusCode() == 200) {
            return $response->getContent();
        }
        throw new RequestException("Failed to get entity, response body:\n" . json_encode($response->getContent()));
    }
}
