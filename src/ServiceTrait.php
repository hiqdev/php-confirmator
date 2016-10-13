<?php

namespace hiqdev\php\confirmator;

trait ServiceTrait
{
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

    protected function readToken($string)
    {
        return $this->getStorage()->has($string) ? json_decode($this->getStorage()->get($string), true) : null;
    }

    protected function writeToken(Token $token)
    {
        return $this->getStorage()->set($token->toString(), json_encode($token->mget()));
    }
}