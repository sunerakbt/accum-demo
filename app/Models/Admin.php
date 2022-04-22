<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Admin extends BaseModel
{
    use HasFactory;

    //protected $hidden = ["password"];

    public function role()
    {
        return $this->embedsOne(Role::class);
    }
}
