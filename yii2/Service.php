<?php

namespace hiqdev\yii2\confirmator;

use hiqdev\php\confirmator\ServiceTrait;
use Yii;

class Service extends \yii\base\Component
{
    use ServiceTrait;

    protected $_storage;

    public function setStorage($value)
    {
        $this->_storage = $value;
    }

    public function getStorage()
    {
        if (is_array($this->_storage)) {
            $config = $this->_storage;
            if (isset($config['path']) && is_string($config['path'])) {
                $config['path'] = Yii::getAlias($config['path']);
            }
            $this->_storage = Yii::createObject($config);
        }

        return $this->_storage;
    }
}
