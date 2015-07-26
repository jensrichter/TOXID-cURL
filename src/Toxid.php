<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 15.07.2015
 * Time: 20:56
 */

namespace Toxid;

use GuzzleHttp\Client;
use GuzzleHttp\Message\ResponseInterface;
use JMS\Serializer\Exception\LogicException;
use Toxid\Cache\CacheException;
use Toxid\Cache\CacheInterface;
use Toxid\Cache\CacheItemInterface;
use Toxid\CMS\EndpointInterface;
use Toxid\CMS\Request as CMSRequest;
use Toxid\CMS\RequestBuilderInterface;
use Toxid\CMS\ResponseParserInterface;

class Toxid
{
    /**
     * @var RequestBuilderInterface[][]
     */
    protected $requestBuilders = array();

    /**
     * @var CacheInterface[][]
     */
    protected $caches = array();

    /**
     * @var ResponseParserInterface[][]
     */
    protected $responseParsers = array();

    /**
     * @var EndpointInterface
     */
    protected $endPoints;

    public function fetchUrl($url, $section)
    {
        $cacheId = hash('sha256', $url);
        try {
            $cacheItem = $this->fetchCache($cacheId);
        } catch (CacheException $ce) {
            $request        = $this->buildRequest($url);
            $guzzleResponse = $this->doCmsRequest($request);
            $cacheItem      = $this->parseResponse($guzzleResponse, $request->getEndpoint());
        }

        $this->putToCache($cacheId, $cacheItem);

        return $cacheItem->getSectionResponse($section);
    }

    /**
     * Parses the response from guzzle.
     *
     * @param ResponseInterface $response Response from guzzle.
     * @param EndpointInterface $endpoint Used endpoint.
     *
     * @throws LogicException
     * @return CacheInterface
     */
    protected function parseResponse(ResponseInterface $response, EndpointInterface $endpoint)
    {
        krsort($this->responseParsers);
        foreach ($this->responseParsers as $responseParsers) {
            foreach ($responseParsers as $responseParser) {
                if ($responseParser->supports($endpoint)) {
                    return $responseParser->parse($response);
                }
            }
        }

        throw new LogicException('No suitable response parser found!');
    }

    /**
     * @param string $url Requested URL
     *
     * @throws LogicException
     * @return CMSRequest
     */
    protected function buildRequest($url)
    {
        krsort($this->requestBuilders);
        foreach ($this->requestBuilders as $requestBuilders) {
            foreach ($requestBuilders as $requestBuilder) {
                if ($requestBuilder->supports($url)) {
                    return $requestBuilder->build($url);
                }
            }
        }

        throw new LogicException('No suitable request builder found!');
    }

    /**
     * @param CMSRequest $request
     *
     * @return ResponseInterface
     */
    protected function doCmsRequest(CMSRequest $request)
    {
        $requestUrl = sprintf(
            "%s/%s?%s",
            $request->getEndpoint()->getBaseUrl(),
            $request->getPathInfo(),
            http_build_query($request->getQueryParams())
        );

        $requestOptions = [
            'headers' => $request->getHeaders()->all(),
            'cookies' => $request->getCookies()->all(),
            'query'   => $request->getQueryParams()->all(),
        ];

        if (!in_array($request->getRequestMethod(), ['GET', 'HEAD', 'OPTIONS'])) {
            $requestOptions['body'] = $request->getRequestBody();
        }

        if ('' != $request->getAuthUsername()) {
            $requestOptions['auth'] = [$request->getAuthUsername(), $request->getAuthPassword()];
            if ('' != $request->getAuthType()) {
                $requestOptions['auth'][] = $request->getAuthType();
            }
        }

        $http          = new Client();
        $guzzleRequest = $http->createRequest($request->getRequestMethod(), $requestUrl, $requestOptions);

        return $http->send($guzzleRequest);
    }

    /**
     * Tries to fetch the requested content from cache.
     *
     * @param string $url URL of the request.
     *
     * @throws CacheException
     * @return CacheItemInterface
     */
    protected function fetchCache($url)
    {
        krsort($this->caches);
        foreach ($this->caches as $caches) {
            foreach ($caches as $cache) {
                if ($cache->isValid($url)) {
                    return $cache->load($url);
                }
            }
        }

        throw new CacheException('No entry in any cache!', 1);
    }

    /**
     * @param string             $url
     * @param CacheItemInterface $cacheItem
     *
     * @return void
     */
    protected function putToCache($url, CacheItemInterface $cacheItem)
    {
        foreach ($this->caches as $caches) {
            foreach ($caches as $cache) {
                $cache->save($url, $cacheItem);
            }
        }
    }

    public function addCache(CacheInterface $cache, $priority = 0)
    {
        $this->caches[$priority][] = $cache;
    }

    public function addRequestBuilder(RequestBuilderInterface $requestBuilder, $priority = 0)
    {
        $this->requestBuilders[$priority][] = $requestBuilder;
    }

    public function addResponseParser(ResponseParserInterface $responseParser, $priority = 0)
    {
        $this->responseParsers[$priority][] = $responseParser;
    }
}
