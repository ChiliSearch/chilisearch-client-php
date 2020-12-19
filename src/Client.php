<?php

namespace SearChili;

use SearChili\Api\Entity;
use SearChili\Api\Site;
use SearChili\Exception\Exception;
use SearChili\Handler\Curl;
use SearChili\Handler\Http;

/**
 * Class Client
 * @property Site $site
 * @property Entity $entity
 * @package SearChili
 */
class Client implements ClientInterface
{
    /**
     * @var string
     */
    const BASE_URI = 'https://api.searchi.li/alice/v1/';

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
    private $apiKey;

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * Client constructor.
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function __construct($apiKey, $apiSecret = null)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * Request GET
     * @method GET
     * @param string $route
     * @param array $params
     * @return string
     */
    public function get($route, $params = [])
    {
        return $this
            ->buildRequest($route, Http::GET, $params)
            ->send();
    }

    /**
     * Request POST
     * @method POST
     * @param string $route
     * @param array $data
     * @return string
     */
    public function post($route, $data)
    {
        return $this
            ->buildRequest($route, Http::POST, [], $data)
            ->send();
    }

    /**
     * Request PUT
     * @method PUT
     * @param string $route
     * @param array $data
     * @return string
     */
    public function put($route, $data)
    {
        return $this
            ->buildRequest($route, Http::PUT, [], $data)
            ->send();
    }

    /**
     * Request DELETE
     * @method DELETE
     * @param string $route
     * @return string
     */
    public function delete($route)
    {
        return $this
            ->buildRequest($route, Http::DELETE)
            ->send();
    }

    /**
     * @param string $route
     * @param $method
     * @param array $params
     * @param array $data
     * @return Curl
     */
    public function buildRequest($route, $method, $params = [], $data = [])
    {
        $resource = new Curl();
        $query = $this->query(array_merge($params, ['apiKey' => $this->apiKey]));
        if ($this->apiSecret) {
            $resource->addHeader(sprintf('Authorization: Bearer %s', $this->apiSecret));
        }
        $resource->addHeader('Content-type: application/json');
        $resource->setMethod($method);
        $url = sprintf('%s%s%s', self::BASE_URI, $route, $query);
        $resource->setUrl($url);
        if (!empty($data)) {
            $resource->setBody($data);;
        }

        return $resource;
    }

    /**
     * Assemble the query string if there are filter parameters
     * @param $params
     * @return string
     */
    public function query($params)
    {
        $query = '';
        if (!empty($params)) {
            $query = '?' . http_build_query($params);
        }
        return $query;
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        if (!array_key_exists(strtolower($name), static::API_ITEMS)) {
            throw new Exception(sprintf('The class could not be instantiated: %s', $name));
        }
        $class = static::API_ITEMS[$name];
        return new $class($this);
    }
}
