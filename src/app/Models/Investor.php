<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    protected $fillable = ['email', 'name', 'phone', 'user_id'];

    public function investments()
    {
        return $this->hasMany(Investment::class, 'investor_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalInvestedAttribute()
    {
        return $this->investments->sum(function ($investment) {
            return $investment->price;
        });
    }

    public function getEarnedPriceAttribute()
    {
        $sum = 0;
        foreach ($this->investments as $investment) {
            $tokenCount = $investment->tokens->count();
            foreach ($investment->project->incomes as $income) {
                $sum += $income->income_amount * $tokenCount;
            }
        }
        return $sum;
    }

    public function getMostProfitableProjectAttribute()
    {
        $projectEarnings = [];

        foreach ($this->investments as $investment) {
            $project = $investment->project;
            if (!$project) {
                continue;
            }

            $tokenCount = $investment->tokens->count();
            $totalIncome = 0;

            foreach ($project->incomes as $income) {
                $totalIncome += $income->income_amount * $tokenCount;
            }

            if (isset($projectEarnings[$project->id])) {
                $projectEarnings[$project->id]['total'] += $totalIncome;
            } else {
                $projectEarnings[$project->id] = [
                    'project' => $project,
                    'total' => $totalIncome,
                ];
            }
        }

        if (empty($projectEarnings)) {
            return null;
        }

        $mostProfitable = collect($projectEarnings)->sortByDesc('total')->first();

        return $mostProfitable['project'];
    }

    public function getInvestmentsPerYearAttribute()
    {
        $investmentsByYear = [];

        foreach ($this->investments as $investment) {
            $year = $investment->created_at->format('Y');
            $tokenCount = $investment->tokens->count();
            $projectPrice = $investment->project?->price ?? 0;
            $total = $tokenCount * $projectPrice;

            if (!isset($investmentsByYear[$year])) {
                $investmentsByYear[$year] = 0;
            }

            $investmentsByYear[$year] += $total;
        }

        return $investmentsByYear;
    }

    public function getInvestmentAllocationAttribute()
    {
        $allocation = [];
        $totalInvested = $this->total_invested;

        if ($totalInvested == 0) {
            return $allocation;
        }

        foreach ($this->investments as $investment) {
            $projectName = $investment->project?->name ?? 'Unknown Project';
            $investmentValue = $investment->price;

            if (!isset($allocation[$projectName])) {
                $allocation[$projectName] = 0;
            }

            $allocation[$projectName] += $investmentValue;
        }

        foreach ($allocation as $project => $value) {
            $allocation[$project] = round(($value / $totalInvested) * 100, 2);
        }

        return $allocation;
    }
}
