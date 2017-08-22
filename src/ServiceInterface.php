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

interface ServiceInterface
{
    public function issueToken(array $data);

    public function checkToken($token, array $data);

    public function findToken($token);

    public function removeToken($token);
}
