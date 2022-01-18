<?php

use App\DB;
use App\ProductController;
use Symfony\Component\HttpFoundation\Request;

include_once __DIR__ . '/vendor/autoload.php';

DB::init([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database.sqlite',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$request = Request::createFromGlobals();

$controller = new ProductController($request);

$controller->response();
