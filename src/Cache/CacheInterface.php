<?php

namespace Toxid\Cache;

interface CacheInterface
{
    /**
     * Determines whether an entry is valid or not.
     *
     * @param string $id ID of the entry.
     *
     * @return bool
     */
    public function isValid($id);

    /**
     * Saves an entry to the cache.
     *
     * @param string $id      ID of the entry.
     * @param string $content Content to save.
     *
     * @return void
     */
    public function save($id, $content);

    /**
     * Loads an entry from cache.
     *
     * @param string $id ID of the entry.
     *
     * @return mixed
     */
    public function load($id);

    /**
     * Invalidates one cache entry.
     *
     * @param string $id ID of the entry.
     *
     * @return void
     */
    public function invalidate($id);

    /**
     * Clears the whole cache.
     *
     * @return void
     */
    public function clear();
}
