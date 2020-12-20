<?php


namespace SearChili;


use SearChili\Exception\Exception;
use SearChili\Handler\Curl;
use SearChili\Handler\Http;

abstract class AbstractClient implements ClientInterface
{
    /**
     * apiKey or apiSecret
     * @return string
     */
    abstract protected function getApiBearerToken();

    /**
     * Request GET
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
        $query = $this->query($params);
        $resource->addHeader(sprintf('Authorization: Bearer %s', $this->getApiBearerToken()));
        $resource->addHeader('Content-type: application/json');
        $resource->setMethod($method);
        $url = sprintf('%s%s%s', static::BASE_URI, $route, $query);
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
