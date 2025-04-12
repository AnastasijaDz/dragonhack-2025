<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landlord extends Model
{
    protected $fillable = ['email', 'name', 'phone', 'user_id'];

    public function projects() {
        return $this->hasMany(Project::class, 'landlord_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
