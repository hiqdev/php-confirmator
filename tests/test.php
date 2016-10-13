#!/usr/bin/env php
<?php

use hiqdev\php\confirmator\Token;

require '_bootstrap.php';

$token = new Token([
    'action' => 'test',
]);

var_dump($token);
