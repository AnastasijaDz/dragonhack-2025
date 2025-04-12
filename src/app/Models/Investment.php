<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = ['investitor_id', 'project_id'];

    public function investirtor() {
        return $this->belongsTo(Investitor::class, 'investitor_id', 'id');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function tokens() {
        return $this->hasMany(Token::class, 'investment_id', 'id');
    }
}
