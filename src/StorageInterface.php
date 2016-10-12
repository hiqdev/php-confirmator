<?php

namespace hiqdev\php\confirmator;

interface StorageInterface
{
    public function has($name);
    public function get($name);
    public function set($name, $text);
}
