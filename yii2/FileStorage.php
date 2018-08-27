<?php
/**
 * Library for confirmation tokens.
 *
 * @link      https://github.com/hiqdev/php-confirmator
 * @package   php-confirmator
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\yii2\confirmator;

use hiqdev\php\confirmator\StorageInterface;
use Yii;

class FileStorage implements StorageInterface
{
    public $path;

    public function __construct($path = null)
    {
        $this->path = Yii::getAlias($path);
    }

    protected function getFullPath($name)
    {
        return $this->path . '/' . $name[0] . '/' . $name;
    }

    public function has($name)
    {
        return is_file($this->getFullPath($name));
    }

    public function get($name)
    {
        $path = $this->getFullPath($name);

        return is_file($path) ? file_get_contents($this->getFullPath($name)) : null;
    }

    public function set($name, $text)
    {
        $path = $this->getFullPath($name);
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return file_put_contents($path, $text);
    }

    public function remove($name)
    {
        $path = $this->getFullPath($name);

        return unlink($path);
    }
}
