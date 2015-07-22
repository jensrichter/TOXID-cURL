<?php
namespace Toxid\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Toxid\CMS\Request as CMSRequest;

class GetRequestEvent extends Event
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var CMSRequest
     */
    protected $cmsRequest;

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return CMSRequest
     */
    public function getCmsRequest()
    {
        return $this->cmsRequest;
    }

    /**
     * @param CMSRequest $cmsRequest
     */
    public function setCmsRequest($cmsRequest)
    {
        $this->cmsRequest = $cmsRequest;
    }
}
