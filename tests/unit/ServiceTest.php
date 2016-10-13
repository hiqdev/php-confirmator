<?php

/*
 * Library for confirmation tokens
 *
 * @link      https://github.com/hiqdev/php-confirmator
 * @package   php-confirmator
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\php\confirmator\tests\unit;

use hiqdev\php\confirmator\FileStorage;
use hiqdev\php\confirmator\Service;
use hiqdev\php\confirmator\Token;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    public $service;
    public $storage;

    public $action = 'restore-password';
    public $username = 'somebody';
    public $notAfter;
    public $notBefore;

    protected function setUp()
    {
        $this->storage = new FileStorage(dirname(__DIR__) . '/tokens');
        $this->service = new Service($this->storage);
        $this->notAfter = date('Y-m-d H:i:s', time() + 3600);
        $this->notBefore = date('Y-m-d H:i:s', time() - 3600);
    }

    protected function tearDown()
    {
        $this->storage = null;
        $this->service = null;
    }

    public function issueToken()
    {
        return $this->service->issueToken([
            'action'    => $this->action,
            'username'  => $this->username,
            'notAfter'  => $this->notAfter,
            'notBefore' => $this->notBefore,
        ]);
    }

    public function testCheckToken()
    {
        $token = $this->issueToken();
        $this->assertTrue($this->service->checkToken($token, [
            'action'    => $this->action,
            'username'  => $this->username,
        ]));
        $this->assertTrue($this->service->checkToken($token->toString(), [
            'action'    => $this->action,
            'username'  => $this->username,
        ]));
        $this->assertFalse($this->service->checkToken($token->toString(), [
            'action'    => 'other',
        ]));
    }

    public function testOutdatedToken()
    {
        $token = $this->service->issueToken([
            'notAfter' => $this->notBefore,
        ]);
        $this->assertFalse($token->check([]));
        $token = $this->service->issueToken([
            'notBefore' => $this->notAfter,
        ]);
        $this->assertFalse($token->check([]));
    }

    public function testFindToken()
    {
        $tokenString = $this->issueToken()->toString();
        $token = $this->service->findToken($tokenString);
        $this->assertInstanceOf(Token::class, $token);
        $this->assertSame($this->action, $token->get('action'));
        $this->assertSame($this->username, $token->get('username'));
        $this->assertSame(null, $token->get('nonExistentField'));
    }
}
