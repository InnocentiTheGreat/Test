<?php

use Acme\RenderCommand;
use Acme\SayHelloCommand;
use Symfony\Component\Console\Application;

require 'vendor/autoload.php';

$app = new Application('Laracasts demo', '1.0');

$app->add(new SayHelloCommand());
$app->add(new RenderCommand());

$app->run();