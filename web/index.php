<?php

$loader = require_once __DIR__.'/../vendor/autoload.php';

$app = new \Framework\Application(__DIR__.'/../app/config/config.php');

$app->run();