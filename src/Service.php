<?php

namespace hiqdev\php\confirmator;

class Service
{
    public $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function issueToken(array $data)
    {
        $token = new Token($data);
        $this->writeToken($token);

        return $token;
    }

    public function checkToken($token, array $data)
    {
        $token = $this->findToken($token);
        if (!$token) {
            return false;
        }

        return $token->check($data);
    }

    public function findToken($token)
    {
        if ($token instanceof Token) {
            return $token;
        }

        $data = $this->readToken($token);

        return empty($data) ? null : new Token($data, $token);
    }

    public function readToken($string)
    {
        return $this->storage->has($string) ? json_decode($this->storage->get($string), true) : null;
    }

    public function writeToken(Token $token)
    {
        return $this->storage->set($token->toString(), json_encode($token->mget()));
    }
}
