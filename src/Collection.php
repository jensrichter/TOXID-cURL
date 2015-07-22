<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 15.07.2015
 * Time: 21:15
 */

namespace Toxid;

use Traversable;

interface Collection extends \IteratorAggregate, \Countable
{
    /**
     * @param mixed $item
     *
     * @return void
     */
    public function add($item);

    /**
     * @param mixed $item
     *
     * @return void
     */
    public function remove($item);
}
