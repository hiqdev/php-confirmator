<?php
declare(strict_types=1);

namespace hiqdev\yii2\confirmator\Event;

use yii\base\Event;
use yii\web\IdentityInterface;

class AfterMailTokenEvent extends Event
{
    public IdentityInterface $identity;

    public string $action;
}
