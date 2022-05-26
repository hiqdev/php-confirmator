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

class Token
{
    protected $service;

    protected $data = [];

    protected $string;

    public function __construct(ServiceInterface $service, array $data = [], $string = null)
    {
        $this->service = $service;
        $this->data = $this->prepareData($data);
        $this->string = $string ?: $this->genString();
    }

    protected function prepareData(array $data)
    {
        if (empty($data['notAfter'])) {
            $data['notAfter'] = '+ 1 week';
        }
        foreach ($data as $key => &$value) {
            if ($key === 'notAfter' || $key === 'notBefore') {
                $value = date('Y-m-d H:i:s', strtotime($value));
            }
        }

        return $data;
    }

    public function __toString()
    {
        return $this->string;
    }

    public function genString($length = 32)
    {
        $res = '';
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $last = strlen($chars) - 1;
        for ($i = 0; $i < $length; ++$i) {
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

    public function remove()
    {
        $this->service->removeToken($this->string);
    }

    public function check(array $data)
    {
        return $this->checkNotAfter() && $this->checkNotBefore() && $this->checkData($data);
    }

    public function checkData(array $data)
    {
        foreach ($data as $key => $value) {
            // TODO: Emergency hack to make email change work. Need to figure out.
            $emailConfirms = ['clientConfirmEmail', 'contactConfirmEmail'];
            if ($key === 'what' && in_array($value, $emailConfirms, true) && in_array($this->get($key), $emailConfirms, true)) {
                continue;
            }
            if ((string)$value !== (string)$this->get($key)) {
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

        return time() > strtotime($this->data['notBefore']);
    }

    public function checkNotAfter()
    {
        if (empty($this->data['notAfter'])) {
            return true;
        }

        return time() < strtotime($this->data['notAfter']);
    }
}
