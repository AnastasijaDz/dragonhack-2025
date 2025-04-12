<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['landlord_id', 'name', 'description', 'amount', 'price'];

    public function landlord() {
        return $this->belongsTo(Landlord::class, 'landlord_id', 'id');
    }

    public function investments() {
        return $this->hasMany(Investment::class, 'projetc_id', 'id');
    }
}
