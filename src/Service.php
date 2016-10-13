<?php

namespace hiqdev\php\confirmator;

class Service
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
