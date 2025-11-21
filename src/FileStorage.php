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

class FileStorage implements StorageInterface
{
    public $path;

    public function __construct($path = null)
    {
        $this->path = $path;
    }

    protected function getFullPath($name)
    {
        if (empty($name)) {
            return null;
        }
        return $this->path . '/' . $name[0] . '/' . $name;
    }

    public function has($name)
    {
        $path = $this->getFullPath($name);
        if ($path === null) {
            return false;
        }
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
            try {
                $success = mkdir($dir, 0755, true);
            } catch (\Throwable $e) {
                $success = false;
            }
            if (!$success) {
                throw new \Exception('Could not create storage in ' . $dir);
            }
        }

        try {
            $success = file_put_contents($path, $text);
        } catch (\Throwable $e) {
            $success = false;
        }

        if ($success === false) {
            throw new \Exception('Failed write file: ' . $path);
        }

        return $success;
    }

    public function remove($name)
    {
        $path = $this->getFullPath($name);

        if ($path === null) {
            return true;
        }

        return unlink($path);
    }
}
