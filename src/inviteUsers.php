#!/usr/bin/env php
<?php
/**
 * https://myaccount.google.com/lesssecureapps
 */
require __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Console\Application;
use IESElCaminas\InviteUsersCommand;
$config = require_once __DIR__ . '/config.php';

$app = new Application('Console App', 'v1.0.0');
$command = new InviteUsersCommand($config);
$app -> add($command);
$app->setDefaultCommand($command->getName());
$app -> run();
