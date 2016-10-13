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
            if (isset($this->_storage['path'])) {
                $this->_storage['path'] = Yii::getAlias($this->_storage['path']);
            }
        }
    }
}
