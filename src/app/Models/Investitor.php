<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investitor extends Model
{
    protected $fillable = ['email', 'name', 'phone',];

    public function investments() {
        return $this->hasMany(Investment::class, 'investitor_id', 'id');
    }
}
