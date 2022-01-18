<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;

    public $fillable = [
        'name', 'unit', 'price', 'expires_at', 'available_inventory', 'image_path'
    ];
}
