<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landlord extends Model
{
    protected $fillable = ['email', 'name', 'phone',];

    public function projects() {
        return $this->hasMany(Project::class, 'landlord_id', 'id');
    }
}
