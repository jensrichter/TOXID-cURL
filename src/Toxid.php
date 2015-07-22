<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 15.07.2015
 * Time: 20:56
 */

namespace Toxid;

use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Toxid\CMS\EndpointInterface;
use Toxid\Event\CacheEvent;
use Toxid\Event\GetRequestEvent;
use Toxid\CMS\Request as CMSRequest;
use GuzzleHttp\Message\ResponseInterface;
use Toxid\Event\ParseResponseEvent;

class Toxid
{
    const EVENT_CACHE_FETCH    = 'cache:fetch';
    const EVENT_CACHE_UPDATE   = 'cache:update';
    const EVENT_GET_REQUEST    = 'request:get';
    const EVENT_PARSE_RESPONSE = 'response:parse';

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var EndpointInterface
     */
    protected $endPoints;

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function fetchUrl($url)
    {
        $cacheEvent = new CacheEvent();
        $cacheEvent->setUrl($url);
        $this->eventDispatcher->dispatch(self::EVENT_CACHE_FETCH, $cacheEvent);

        if ($cacheEvent->getResult() == CacheEvent::RESULT_CACHE_HIT) {
            return $cacheEvent->getData();
        }

        $requestEvent = new GetRequestEvent();
        $requestEvent->setRequest(Request::createFromGlobals());
        $this->eventDispatcher->dispatch(self::EVENT_GET_REQUEST, $requestEvent);

        $parseResponseEvent = new ParseResponseEvent();
        $parseResponseEvent->setResponse($this->doCmsRequest($requestEvent->getCmsRequest()));
        $this->eventDispatcher->dispatch(self::EVENT_PARSE_RESPONSE, $parseResponseEvent);


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
}
