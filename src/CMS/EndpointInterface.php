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

    /**
     * HTTP authentication type.
     *
     * @return string
     */
    public function getAuthType();

    /**
     * Username for HTTP authentication.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Password for HTTP authentication.
     *
     * @return string
     */
    public function getPassword();
}
