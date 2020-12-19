<?php

namespace SearChili\Handler;

use SearChili\Exception\RequestException;
use SearChili\Exception\ServerErrorRequestException;
use SearChili\Exception\Exception;
use SearChili\Exception\TooManyRequestsRequestException;
use SearChili\Exception\UnauthorizedRequestException;

class Curl
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var Response
     */
    private $response;

    /**
     * @method init
     */
    public function __construct()
    {
        $this->resource = curl_init();
        curl_setopt($this->resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->resource, CURLOPT_SSL_VERIFYPEER, true);

        $this->response = new Response();
    }

    /**
     * @param string $url
     * @return void
     */
    public function setUrl($url)
    {
        curl_setopt($this->resource, CURLOPT_URL, $url);
    }

    /**
     * @param string $method
     * @return void
     */
    public function setMethod($method)
    {
        curl_setopt($this->resource, CURLOPT_CUSTOMREQUEST, $method);
    }

    /**
     * @param array $data
     * @return void
     */
    public function setBody($data)
    {
        $body = $this->serialize($data);
        curl_setopt($this->resource, CURLOPT_POST, true);
        curl_setopt($this->resource, CURLOPT_POSTFIELDS, $body);
    }

    /**
     * Parses the response to return in JSON format
     * @param $data
     * @return string
     * @throws Exception
     */
    public function serialize($data)
    {
        if (!is_array($data)) {
            throw new Exception("The data must be an array.");
        }
        return json_encode($data);
    }

    /**
     * @param string @header
     * @return void
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
    }

    /**
     * @param array $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return void
     */
    private function buildHeaders()
    {
        curl_setopt(
            $this->resource, CURLOPT_HTTPHEADER,
            $this->getHeaders()
        );
    }

    /**
     * @return array
     */
    public function exec()
    {
        $this->buildHeaders();
        $data = curl_exec($this->resource);
        $info = curl_getinfo($this->resource);

        return [
            (int)$info['http_code'],
            $data,
        ];
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function send()
    {
        try {
            list($httpCode, $body) = $this->exec();
        } catch (\Exception $ex) {
            throw new ServerErrorRequestException('Failed to execute the request.', 500, $ex);
        }

        switch ($httpCode) {
            case 401:
                throw new UnauthorizedRequestException('Failed to authorize. Make sure API Key and API Secret are set.',
                    401);
            case 429:
                throw new TooManyRequestsRequestException('Failed to authorize. Make sure API Key and API Secret are set.',
                    401);
            default:
                $objBody = json_decode($body, true);
                if (!is_array($objBody)) {
                    throw new RequestException(
                        sprintf("Invalid json body, could not json_decode the body. httpCode: %d , body:\n%s",
                            $httpCode, $body),
                        $httpCode
                    );
                }
                return $this->response
                    ->setStatusCode($httpCode)
                    ->setContent($objBody);
        }
    }

    /**
     * @destruct method
     */
    public function __destruct()
    {
        curl_close($this->resource);
    }
}
