<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    public function trees() {
        return $this->hasMany(Tree::class, 'farmer_id', 'id');
    }
}
