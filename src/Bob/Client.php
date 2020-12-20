<?php

namespace SearChili\Bob;

use SearChili\AbstractClient;
use SearChili\Bob\Api\Entity;
use SearChili\Bob\Api\Site;

/**
 * Class Client
 * @property Site $site
 * @property Entity $entity
 * @package SearChili
 */
class Client extends AbstractClient
{
    /**
     * @var string
     */
    const BASE_URI = 'https://api.searchi.li/bob/v1/';

    /**
     * @var array Items via __get
     */
    const API_ITEMS = [
        'site' => Site::class,
        'entity' => Entity::class,
    ];

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * Client constructor.
     * @param string $apiSecret
     */
    public function __construct($apiSecret)
    {
        $this->apiSecret = $apiSecret;
    }

    protected function getApiBearerToken()
    {
        return $this->apiSecret;
    }
}
