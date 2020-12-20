<?php

namespace SearChili\Alice;

use SearChili\AbstractClient;
use SearChili\Alice\Api\Entity;

/**
 * Class Client
 * @property Entity $entity
 * @package SearChili
 */
class Client extends AbstractClient
{
    /**
     * @var string
     */
    const BASE_URI = 'https://api.searchi.li/alice/v1/';

    /**
     * @var array Items via __get
     */
    const API_ITEMS = [
        'entity' => Entity::class,
    ];

    /**
     * @var string
     */
    private $apiKey;

    /**
     * Client constructor.
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    protected function getApiBearerToken()
    {
        return $this->apiKey;
    }
}
