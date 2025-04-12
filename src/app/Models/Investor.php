<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    protected $fillable = ['email', 'name', 'phone', 'user_id'];

    public function investments() {
        return $this->hasMany(Investment::class, 'investor_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
