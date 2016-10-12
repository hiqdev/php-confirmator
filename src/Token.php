<?php

namespace hiqdev\php\confirmator;

class Token
{
    protected $data = [];

    protected $string;

    public function __construct(array $data = [], $string = null)
    {
        $this->data = $data;
        $this->string = $string ?: $this->genString();
    }

    public function toString()
    {
        return $this->string;
    }

    public function genString($length = 32)
    {
        $res = '';
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $last = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $res .= $chars[mt_rand(0, $last)];
        }

        return $res;
    }

    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    public function mget()
    {
        return $this->data;
    }

    public function check(array $data)
    {
        return $this->checkNotAfter() && $this->checkNotBefore() && $this->checkData($data);
    }

    public function checkData(array $data)
    {
        foreach ($data as $key => $value) {
            if ($value !== $this->get($key)) {
                return false;
            }
        }

        return true;
    }

    public function checkNotBefore()
    {
        if (empty($this->data['notBefore'])) {
            return true;
        }

        return time()>strtotime($this->data['notBefore']);
    }

    public function checkNotAfter()
    {
        if (empty($this->data['notAfter'])) {
            return true;
        }

        return time()<strtotime($this->data['notAfter']);
    }
}
