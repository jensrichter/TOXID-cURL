<?php

namespace Toxid\CMS;

use Symfony\Component\HttpFoundation\ParameterBag;

class Request
{
    /**
     * @var EndpointInterface
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $pathInfo;

    /**
     * @var ParameterBag
     */
    protected $queryParams;

    /**
     * @var string
     */
    protected $requestMethod;

    /**
     * @var ParameterBag
     */
    protected $cookies;

    /**
     * @var ParameterBag
     */
    protected $headers;

    /**
     * @var string
     */
    protected $requestBody;

    /**
     * @var string
     */
    protected $authUsername;

    /**
     * @var string
     */
    protected $authPassword;

    /**
     * @var string
     */
    protected $authType;

    /**
     * @return EndpointInterface
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param EndpointInterface $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getPathInfo()
    {
        return $this->pathInfo;
    }

    /**
     * @param string $pathInfo
     */
    public function setPathInfo($pathInfo)
    {
        $this->pathInfo = $pathInfo;
    }

    /**
     * @return ParameterBag
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * @param ParameterBag $queryParams
     */
    public function setQueryParams($queryParams)
    {
        $this->queryParams = $queryParams;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @param string $requestMethod
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * @return ParameterBag
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @param ParameterBag $cookies
     */
    public function setCookies($cookies)
    {
        $this->cookies = $cookies;
    }

    /**
     * @return ParameterBag
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param ParameterBag $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * @param string $requestBody
     */
    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
    }

    /**
     * @return string
     */
    public function getAuthUsername()
    {
        return $this->authUsername;
    }

    /**
     * @param string $authUsername
     */
    public function setAuthUsername($authUsername)
    {
        $this->authUsername = $authUsername;
    }

    /**
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->authPassword;
    }

    /**
     * @param string $authPassword
     */
    public function setAuthPassword($authPassword)
    {
        $this->authPassword = $authPassword;
    }

    /**
     * @return string
     */
    public function getAuthType()
    {
        return $this->authType;
    }

    /**
     * @param string $authType
     */
    public function setAuthType($authType)
    {
        $this->authType = $authType;
    }
}
