<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['project_id', 'income_amount'];

    public function project() {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
