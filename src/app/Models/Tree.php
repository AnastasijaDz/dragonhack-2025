<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    public function farmer() {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'id');
    }
}
