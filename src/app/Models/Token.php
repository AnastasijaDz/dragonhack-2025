<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = ['investment_id', 'key'];

    public function investment() {
        return $this->belongsto(Investment::class, 'investment_id', 'id');
    }
}
