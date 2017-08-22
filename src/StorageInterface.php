<?php
/**
 * Library for confirmation tokens.
 *
 * @link      https://github.com/hiqdev/php-confirmator
 * @package   php-confirmator
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\php\confirmator;

interface StorageInterface
{
    public function has($name);

    public function get($name);

    public function set($name, $text);

    public function remove($name);
}
