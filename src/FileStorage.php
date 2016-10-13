<?php

namespace hiqdev\php\confirmator;

class FileStorage implements StorageInterface
{
    public $path;

    public function __construct($path)
    {
        $this->path = $path;
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

}
