<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 15.07.2015
 * Time: 21:15
 */

namespace Toxid\CMS;

use Toxid\Collection;
use Toxid\InvalidArgumentException;
use Traversable;

class EndpointCollection implements  Collection
{
    /**
     * @var EndpointInterface[]
     */
    protected $endpoints = array();

    /**
     * @param EndpointInterface $item
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function add($item)
    {
        if (!$item instanceof EndpointInterface) {
            throw new InvalidArgumentException('The $item parameter MUST be an instance of EndpointInterface!');
        }

        $this->endpoints[] = $item;
    }

    /**
     * @param EndpointInterface $item
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function remove($item)
    {
        if (!$item instanceof EndpointInterface) {
            throw new InvalidArgumentException('The $item parameter MUST be an instance of EndpointInterface!');
        }

        for ($key = 0, $count = $this->count(); $key < $count; $key++) {
            if ($this->endpoints[$key] === $item) {
                unset($this->endpoints[$key]);
            }
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *       <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->endpoints);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *       </p>
     *       <p>
     *       The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->endpoints);
    }

}
