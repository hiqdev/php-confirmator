<?php
/**
 * Library for confirmation tokens.
 *
 * @link      https://github.com/hiqdev/php-confirmator
 * @package   php-confirmator
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2021, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\yii2\confirmator;

use hiqdev\php\confirmator\ServiceInterface;
use hiqdev\php\confirmator\ServiceTrait;
use hiqdev\php\confirmator\StorageInterface;
use hiqdev\yii2\confirmator\Event\AfterMailTokenEvent;
use Yii;
use yii\base\Component;
use yii\helpers\Inflector;
use yii\log\Logger;
use yii\mail\MailerInterface;
use yii\web\IdentityInterface;

class Service extends Component implements ServiceInterface, MailTokenInterface
{
    const EVENT_AFTER_MAIL_TOKEN = 'afterMailToken';

    use ServiceTrait;

    public string $mailTokenLifetime;

    private MailerInterface $mailer;
    private Logger $logger;

    public function __construct(
        StorageInterface $storage,
        MailerInterface $mailer,
        Logger $logger,
        array $config = []
    ) {
        parent::__construct($config);

        $this->storage = $storage;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function mailToken(IdentityInterface $user, string $action, array $data = []): void
    {
        if (Yii::$app->has('authManager')) {
            $auth = Yii::$app->authManager;
            if ($auth->getItem($action) && !$auth->checkAccess($user->id, $action)) {
                $this->logger->log('Forbidden to perform mail token action', Logger::LEVEL_ERROR);
                return;
            }
        }

        if (isset($data['to'])) {
            $to = $data['to'];
            unset($data['to']);
        }

        try {
            $token = $this->issueToken(array_merge([
                'action'    => $action,
                'id'        => $user->id,
                'email'     => $user->email,
                'username'  => $user->username,
                'notAfter'  => "+{$this->mailTokenLifetime}",
            ], $data));
        } catch (\Throwable $e) {
            $this->logger->log($e->getMessage(), Logger::LEVEL_ERROR);
            return;
        }

        $view = lcfirst(Inflector::id2camel($action . '-token'));

        $sendTo = $to ?? $user->email_confirmed ?? $user->email;

        /** @var bool $result */
        $result = $this->mailer->compose()
            ->renderHtmlBody($view, compact('user', 'token'))
            ->setTo([$sendTo => $user->username])
            ->send();

        if (!$result) {
            $this->logger->log('Failed to send email on mail token action', Logger::LEVEL_ERROR);
            return;
        }

        $this->trigger(self::EVENT_AFTER_MAIL_TOKEN, new AfterMailTokenEvent([
            'identity' => $user,
            'action' => $action,
        ]));
    }
}
