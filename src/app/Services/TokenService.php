<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\Token;
use Random\RandomException;

class TokenService
{
    /**
     * Generate tokens for a given investment.
     *
     * @param Investment $investment The investment record to associate the tokens with.
     * @param int $quantity The number of tokens (parcels) to generate.
     * @return void
     * @throws RandomException
     */
    public function generateTokensForInvestment(Investment $investment, int $quantity): void
    {
        for ($i = 0; $i < $quantity; $i++) {
            // Generate a unique token key.
            $tokenKey = bin2hex(random_bytes(16));

            // Create a new token record associated with the investment.
            Token::create([
                'investment_id' => $investment->id,
                'key'           => $tokenKey,
            ]);
        }
    }

    /**
     * Transfer a token to a new investment record.
     *
     * @param Token $token The token to transfer.
     * @param Investment $newInvestment The investment record that will now own the token.
     * @return Token The updated token.
     */
    public function transferToken(Token $token, Investment $newInvestment): Token
    {
        $token->update([
            'investment_id' => $newInvestment->id,
        ]);

        return $token;
    }
}
