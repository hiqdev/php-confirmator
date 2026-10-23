<?php
declare(strict_types=1);

namespace hiqdev\yii2\confirmator;

use yii\web\IdentityInterface;

interface MailTokenInterface
{
    /**
     * Issues a token for the action and emails it to the user.
     *
     * @return bool whether the email was sent. The reason of a failure is logged.
     */
    public function mailToken(IdentityInterface $user, string $action, array $data = []): bool;
}
