<?php

namespace Toxid\CMS;

use GuzzleHttp\Message\ResponseInterface;
use Toxid\Cache\CacheItem;
use Toxid\Cache\CacheItemInterface;

class XMLResponseParser implements ResponseParserInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = (string) $name;
    }

    /**
     * determines whether this response parser is suitable for the given endpoint or not.
     *
     * @param EndpointInterface $endpoint Used endpoint.
     *
     * @return bool
     */
    public function supports(EndpointInterface $endpoint)
    {
        return $endpoint->getName() == $this->name;
    }

    /**
     * Parses a guzzle response into a cachable item.
     *
     * @param ResponseInterface $response Response object from guzzle.
     *
     * @return CacheItemInterface
     */
    public function parse(ResponseInterface $response)
    {
        $cacheItem = CacheItem::fromGuzzleResponse($response);
        $this->parseRecursive($response->xml(), $cacheItem);

        return $cacheItem;
    }

    /**
     * Parses the XML response recursively.
     *
     * @param \SimpleXMLElement  $xml
     * @param CacheItemInterface $cacheItem
     * @param string             $prefix
     *
     * @return void
     */
    protected function parseRecursive(\SimpleXMLElement $xml, CacheItemInterface $cacheItem, $prefix = '')
    {
        /** @var \SimpleXMLElement $child */
        foreach ($xml->children() as $child) {
            $nodeName = $child->getName();
            if (0 < $child->count()) {
                $this->parseRecursive($child, $cacheItem, "{$prefix}{$nodeName}.");
                continue;
            }

            $cacheItem->addSection("{$prefix}{$nodeName}", (string) $child);
        }
    }

}
