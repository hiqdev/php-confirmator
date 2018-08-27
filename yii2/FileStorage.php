<?php

namespace hiqdev\yii2\confirmator;

use Yii;

class FileStorage extends \hiqdev\php\confirmator\FileStorage
{
    public function __construct($path = null)
    {
        parent::__construct(Yii::getAlias($path));
    }
}
