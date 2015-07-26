<?php

namespace Toxid\CMS;

use GuzzleHttp\Message\ResponseInterface;
use Toxid\Cache\CacheItemInterface;

interface ResponseParserInterface
{
    /**
     * determines whether this response parser is suitable for the given endpoint or not.
     *
     * @param EndpointInterface $endpoint Used endpoint.
     *
     * @return bool
     */
    public function supports(EndpointInterface $endpoint);

    /**
     * Parses a guzzle response into a cachable item.
     *
     * @param ResponseInterface $response Response object from guzzle.
     *
     * @return CacheItemInterface
     */
    public function parse(ResponseInterface $response);
}
