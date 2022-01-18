<?php

namespace App;

use Illuminate\Database\Capsule\Manager as Capsule;

class DB extends Capsule
{
    public static function init($config = [])
    {
        $capsule = new Capsule();

        $capsule->addConnection($config);

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
    }
}
