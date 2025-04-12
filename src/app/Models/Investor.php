<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    protected $fillable = ['email', 'name', 'phone',];

    public function investments() {
        return $this->hasMany(Investment::class, 'investor_id', 'id');
    }
}
