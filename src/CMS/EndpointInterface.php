<?php

namespace Toxid\CMS;

interface EndpointInterface
{
    /**
     * Returns the name of the endpoint (e.g. Typo3, WordPress)
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the base URL to the endpoint.
     *
     * @return string
     */
    public function getBaseUrl();
}
