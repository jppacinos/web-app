<?php

use Illuminate\Database\Schema\Blueprint;

include_once __DIR__ . '/vendor/autoload.php';

App\DB::init([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database.sqlite',
    // 'host' => 'localhost',
    // 'username' => 'root',
    // 'password' => 'password',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

/**
 * drop products table
 */
App\DB::schema()->drop('products');

/**
 * create products table
 */
App\DB::schema()->create('products', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->string('unit');
    $table->decimal('price', 8, 2)->default(0.00);
    $table->date('expires_at');
    $table->integer('available_inventory')->default(0);
    $table->string('image_path')->nullable();
});

/**
 * create sample data for products table
 */
App\DB::table('products')->insert([
    'name' => 'Coke',
    'unit' => 'bottle',
    'price' => 60.00,
    'expires_at' => '2022-01-16',
    'available_inventory' => 20,
    'image_path' => '/images/coke.jpg'
]);
