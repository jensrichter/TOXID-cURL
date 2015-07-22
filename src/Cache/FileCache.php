<?php

namespace Toxid\Cache;

use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Toxid\Cache\FileCache\FileSystemException;

class FileCache implements CacheInterface
{

    /**
     * @var \DateInterval
     */
    private $ttl;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * Determines whether an entry is valid or not.
     *
     * @param string $id ID of the entry.
     *
     * @return bool
     */
    public function isValid($id)
    {
        $metadataFile = "{$this->cacheDir}/{$id}.cache.meta";
        if (!file_exists($metadataFile)) {
            return false;
        }

        $serializer = SerializerBuilder::create()->build();
        $metadata   = $serializer->deserialize(file_get_contents($metadataFile), 'array', 'json');

        /** @var \DateTime $lastUpdate */
        $lastUpdate = $metadata['lastUpdate'];
        $updateTime = $lastUpdate->add($this->ttl);
        if (new \DateTime() <= $updateTime) {
            return false;
        }

        return true;
    }

    /**
     * Saves an entry to the cache.
     *
     * @param string $id      ID of the entry.
     * @param mixed  $content Content to save.
     *
     * @throws FileSystemException
     *
     * @return void
     */
    public function save($id, $content)
    {
        $this->checkCacheDirAccess();

        $cacheFile    = "{$this->cacheDir}/{$id}.cache";
        $serializer   = SerializerBuilder::create()->build();
        $cacheContent = $serializer->serialize($content, 'json');
        file_put_contents($cacheFile, $cacheContent);

        $type = $this->detectDataType($content);

        $metadata = $serializer->serialize(['lastUpdate' => new \DateTime(), 'type' => $type], 'json');
        file_put_contents("{$cacheFile}.meta", $metadata);
    }

    /**
     * Loads an entry from cache.
     *
     * @param string $id ID of the entry.
     *
     * @throws FileSystemException
     * @throws CacheException
     *
     * @return mixed
     */
    public function load($id)
    {
        $this->checkCacheDirAccess();

        $cacheFile = "{$this->cacheDir}/{$id}.cache";
        if (!file_exists($cacheFile)) {
            throw new FileSystemException("The cache file '{$cacheFile}' does NOT exist!");
        }

        if (!$this->isValid($id)) {
            throw new CacheException("The cache file '{$cacheFile}' is NOT valid!");
        }

        $serializer = SerializerBuilder::create()->build();
        $metadata   = $serializer->deserialize(file_get_contents("{$cacheFile}.meta"), 'array', 'json');

        return $serializer->deserialize(file_get_contents($cacheFile), $metadata['type'], 'json');
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
        $this->checkCacheDirAccess();

        $cacheFile    = "{$this->cacheDir}/{$id}.cache";
        $metadataFile = "{$cacheFile}.meta";
        $fs           = new Filesystem();
        $fs->remove([$cacheFile, $metadataFile]);
    }

    /**
     * Clears the whole cache.
     *
     * @return void
     */
    public function clear()
    {
        $finder = new Finder();
        $finder
            ->in($this->cacheDir)
            ->name('*.cache')
            ->name('*.cache.meta');

        $fs = new Filesystem();
        $fs->remove($finder->files());
    }

    /**
     * Initializes a new instance.
     *
     * @param string $cacheDir Path to store the files.
     * @param int    $ttl      Time in seconds when an entry will become invalid.
     *
     * @return FileCache
     */
    public function __construct($cacheDir, $ttl = 3600)
    {
        $this->cacheDir = $cacheDir;
        $this->ttl = new \DateInterval("PT{$ttl}S");
    }

    /**
     * @throws FileSystemException
     */
    private function checkCacheDirAccess()
    {
        $filename = $this->cacheDir;

        if (!file_exists($filename)) {
            if (!@mkdir($filename, 0775)) {
                throw new FileSystemException(
                    "The cache directory '{$filename}' does NOT exist and could NOT be created!"
                );
            }
        }

        $this->checkFileAccess($filename);
    }

    /**
     * @param mixed $data
     *
     * @return string
     */
    private function detectDataType($data)
    {
        $type = gettype($data);
        if ('object' == $type) {
            return get_class($data);
        }

        return $type;
    }

    /**
     * @param string $filename
     * @param bool   $checkExistence
     *
     * @throws FileSystemException
     */
    private function checkFileAccess($filename, $checkExistence = false)
    {
        if ($checkExistence && !file_exists($filename)) {
            throw new FileSystemException("The path '{$filename}' does NOT exist!");
        }

        if (!is_readable($filename)) {
            throw new FileSystemException("The path '{$filename}' is NOT readable!");
        }

        if (!is_writable($filename)) {
            throw new FileSystemException("The path '{$filename}' is NOT writable!");
        }
    }
}
