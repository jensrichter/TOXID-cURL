<?php

namespace Toxid\CMS;

interface RequestBuilderInterface
{
    /**
     * Determines whether this request builder should be used, or not.
     *
     * @param string $url Requested URL.
     *
     * @return bool
     */
    public function supports($url);

    /**
     * Builds the request which will be send to the CMS.
     *
     * @param string $url Requested URL.
     *
     * @return Request
     */
    public function build($url);
}
