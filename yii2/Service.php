<?php
/**
 * Library for confirmation tokens.
 *
 * @link      https://github.com/hiqdev/php-confirmator
 * @package   php-confirmator
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\yii2\confirmator;

use hiqdev\php\confirmator\ServiceInterface;
use hiqdev\php\confirmator\ServiceTrait;
use hiqdev\php\confirmator\StorageInterface;
use Yii;
use yii\helpers\Inflector;

class Service extends \yii\base\Component implements ServiceInterface
{
    use ServiceTrait;

    protected $_storage;

    public function __construct(StorageInterface $storage, array $config = [])
    {
        parent::__construct($config);
        $this->_storage = $storage;
    }

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

    public function mailToken($user, $action, array $data = [])
    {
        if (!$user) {
            return false;
        }

        if (Yii::$app->has('authManager')) {
            $auth = Yii::$app->authManager;
            if ($auth->getItem($action) && !$auth->checkAccess($user->id, $action)) {
                return false;
            }
        }

        $token = $this->issueToken(array_merge([
            'action'    => $action,
            'email'     => $user->email,
            'username'  => $user->username,
            'notAfter'  => '+ 3 days',
        ], $data));

        $view = lcfirst(Inflector::id2camel($action . '-token'));

        $email_confirmed = $user->email_confirmed ?? $user->email;

        return Yii::$app->mailer->compose()
            ->renderHtmlBody($view, compact('user', 'token'))
            ->setTo([$email_confirmed => $user->name])
            ->send();
    }
}
