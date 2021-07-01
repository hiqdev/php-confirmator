<?php
declare(strict_types=1);

namespace hiqdev\yii2\confirmator;

use yii\web\IdentityInterface;

interface MailTokenInterface
{
    public function mailToken(IdentityInterface $user, string $action, array $data = []): void;
}
