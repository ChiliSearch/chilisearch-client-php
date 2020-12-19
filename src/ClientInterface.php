<?php

namespace SearChili;

use SearChili\Handler\Response;

/**
 * ClientInterface
 */
interface ClientInterface
{
    /**
     * @param string $route
     * @param array $params
     * @return Response
     */
    public function get($route, $params = []);

    /**
     * @param string $route
     * @param $data
     * @return Response
     */
    public function post($route, $data);

    /**
     * @param string $route
     * @param $data
     * @return Response
     */
    public function put($route, $data);

    /**
     * @param string $route
     * @return Response
     */
    public function delete($route);
}
