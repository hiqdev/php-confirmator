<?php

/*
 * Library for confirmation tokens
 *
 * @link      https://github.com/hiqdev/php-confirmator
 * @package   php-confirmator
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\php\confirmator;

class Service implements ServiceInterface
{
    use ServiceTrait;

    protected $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function getStorage()
    {
        return $this->storage;
    }
}
