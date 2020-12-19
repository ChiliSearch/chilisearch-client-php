<?php

namespace SearChili\Api;

use SearChili\Exception\RequestException;
use SearChili\Model\Site as SiteModel;

class Site extends Api
{
    /**
     * @return SiteModel
     * @throws RequestException
     */
    public function get()
    {
        $response = $this->client->get('site');
        if ($response->getStatusCode() == 200) {
            return new SiteModel($response->getContent());
        }
        throw new RequestException("Failed to retrieve, response body:\n" . json_encode($response->getContent()));
    }
}
