<?php

namespace Toxid\CMS;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as SFRequest;

class Typo3RequestBuilder implements RequestBuilderInterface
{
    /**
     * @var EndpointInterface
     */
    protected $endpoint;

    /**
     * @param EndpointInterface $endpoint
     */
    public function __construct(EndpointInterface $endpoint)
    {
        $this->endpoint = $endpoint;
    }
    /**
     * Determines whether this request builder should be used, or not.
     *
     * @param string $url Requested URL.
     *
     * @return bool
     */
    public function supports($url)
    {
        return true;
    }

    /**
     * Builds the request which will be send to the CMS.
     *
     * @param string $url Requested URL.
     *
     * @return Request
     */
    public function build($url)
    {
        $request = new Request($this->endpoint);
        $sfRequest = SFRequest::createFromGlobals();
        $request->setPathInfo($url);
        $request->setRequestMethod($sfRequest->getMethod());
        $request->setCookies($sfRequest->cookies);
        $request->setHeaders(new ParameterBag($sfRequest->headers->all()));
        $request->setQueryParams($sfRequest->query);
        $request->setRequestBody($sfRequest->getContent());
        $request->setAuthType($this->endpoint->getAuthType());
        $request->setAuthUsername($this->endpoint->getUsername());
        $request->setAuthPassword($this->endpoint->getPassword());

        return $request;
    }

}
