<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 15.07.2015
 * Time: 21:00
 */

namespace Toxid\Event;

use Symfony\Component\EventDispatcher\Event;

class CacheEvent extends Event
{
    const RESULT_CACHE_HIT = 0;
    const RESULT_CACHE_MISS = 1;
    const RESULT_CACHE_UPDATED = 2;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var int
     */
    protected $result = self::RESULT_CACHE_MISS;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param int $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}
