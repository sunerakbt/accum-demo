<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent; 

class BaseModel extends Moloquent
{
    protected $connection = "mongodb";
}
