<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Customer extends BaseModel
{
    use HasFactory;

    public function transactions()
    {
        return $this->hasMany(Customer::class);
    }
}
