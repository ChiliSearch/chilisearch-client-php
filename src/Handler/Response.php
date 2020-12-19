<?php

namespace SearChili\Handler;

class Response
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var array
     */
    protected $content;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return Response
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array $content
     * @return Response
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}
