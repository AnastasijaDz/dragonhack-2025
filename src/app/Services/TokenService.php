<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\Investor;
use App\Models\Token;
use Random\RandomException;

class TokenService
{
    public function generateTokensForInvestment(Investment $investment, int $quantity): void
    {
        for ($i = 0; $i < $quantity; $i++) {
            $tokenKey = bin2hex(random_bytes(16));
            Token::create([
                'investment_id' => $investment->id,
                'key' => $tokenKey,
            ]);
        }
    }

    public function transferTokensToInvestorByEmail(array $tokenIds, string $recipientEmail, int $projectId): void
    {
        $recipientInvestor = Investor::where('email', $recipientEmail)->firstOrFail();

        $recipientInvestment = Investment::where('investor_id', $recipientInvestor->id)
            ->where('project_id', $projectId)
            ->first();

        if (!$recipientInvestment) {
            $recipientInvestment = Investment::create([
                'investor_id' => $recipientInvestor->id,
                'project_id' => $projectId,
            ]);
        }

        Token::whereIn('id', $tokenIds)
            ->update(['investment_id' => $recipientInvestment->id]);
    }
}
