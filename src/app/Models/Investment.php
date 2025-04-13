<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = ['investor_id', 'project_id'];

    public function investor()
    {
        return $this->belongsTo(Investor::class, 'investor_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function tokens()
    {
        return $this->hasMany(Token::class, 'investment_id', 'id');
    }

    public function getPriceAttribute()
    {
        $tokenCount = $this->tokens->count();
        $projectPrice = $this->project?->price ?? 0;

        return $tokenCount * $projectPrice;
    }
}
