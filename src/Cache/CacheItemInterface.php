<?php
namespace Toxid\Cache;

use Symfony\Component\HttpFoundation\Response as SFResponse;

interface CacheItemInterface
{
    /**
     * Adds as section.
     *
     * @param $name
     * @param $content
     *
     * @return void
     */
    public function addSection($name, $content);

    /**
     * Returns the response object for the given section.
     *
     * @param string $name Name of the section.
     *
     * @return SFResponse
     */
    public function getSectionResponse($name);
}
