<?php

namespace Toxid\Cache;

class MemoryCache implements CacheInterface
{
    /**
     * @var CacheItemInterface[]
     */
    private $cachedItems = [];

    /**
     * Determines whether an entry is valid or not.
     *
     * @param string $id ID of the entry.
     *
     * @return bool
     */
    public function isValid($id)
    {
        return isset($this->cachedItems[$id]);
    }

    /**
     * Saves an entry to the cache.
     *
     * @param string             $id      ID of the entry.
     * @param CacheItemInterface $content Content to save.
     *
     * @return void
     */
    public function save($id, CacheItemInterface $content)
    {
        $this->cachedItems[$id] = $content;
    }

    /**
     * Loads an entry from cache.
     *
     * @param string $id ID of the entry.
     *
     * @return mixed
     */
    public function load($id)
    {
        if ($this->isValid($id)) {
            return $this->cachedItems[$id];
        }

        return null;
    }

    /**
     * Invalidates one cache entry.
     *
     * @param string $id ID of the entry.
     *
     * @return void
     */
    public function invalidate($id)
    {
        unset($this->cachedItems[$id]);
    }

    /**
     * Clears the whole cache.
     *
     * @return void
     */
    public function clear()
    {
        $this->cachedItems = [];
    }

}
