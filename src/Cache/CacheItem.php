<?php

namespace Toxid\Cache;

use GuzzleHttp\Message\ResponseInterface as GResponse;
use Symfony\Component\HttpFoundation\Response as SFResponse;
use JMS\Serializer\Annotation\Type;

/**
 * Class CacheItem
 *
 * @package Toxid\Cache
 */
class CacheItem implements CacheItemInterface
{
    /**
     * @Type("array")
     *
     * @var array
     */
    private $headers;

    /**
     * @Type("integer")
     *
     * @var int
     */
    private $statusCode;

    /**
     * @Type("array")
     *
     * @var array
     */
    private $sections = array();

    /**
     * Initializes the new instance.
     *
     * @param int   $status
     * @param array $headers
     *
     * @return CacheItem
     */
    public function __construct($status = 200, $headers = array())
    {
        $this->statusCode = $status;
        $this->headers    = $headers;
    }

    /**
     * Creates a new instance from a Guzzle response.
     *
     * @param GResponse $response Guzzle response.
     *
     * @return CacheItem
     */
    public static function fromGuzzleResponse(GResponse $response)
    {
        $headers = $response->getHeaders();
        if (isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/html';
        }

        return new self($response->getStatusCode(), $headers);
    }

    /**
     * Adds as section.
     *
     * @param $name
     * @param $content
     *
     * @return void
     */
    public function addSection($name, $content)
    {
        $this->sections[$name] = $content;
    }

    /**
     * Returns the response object for the given section.
     *
     * @param string $name Name of the section.
     *
     * @return SFResponse
     */
    public function getSectionResponse($name)
    {
        if (!isset($this->sections[$name])) {
            return new SFResponse('', 404);
        }

        return new SFResponse($this->sections[$name], $this->statusCode, $this->headers);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param array $sections
     */
    public function setSections($sections)
    {
        $this->sections = $sections;
    }
}
