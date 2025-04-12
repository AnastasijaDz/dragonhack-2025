<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\Token;
use App\Services\TokenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvestmentsController extends Controller
{
    protected TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function index(Request $request): View
    {
        $investments = Investment::all();
        return view('investments.index', compact('investments'));
    }

    public function show(int $id): View
    {
        $investment = Investment::findOrFail($id);
        return view('investments.show', compact('investment'));
    }

    public function create(): View
    {
        return view('investments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'investor_id'    => 'required|exists:investors,id',
            'project_id'     => 'required|exists:projects,id',
            'token_quantity' => 'required|integer|min:1',
        ]);

        $investment = Investment::create([
            'investor_id' => $data['investor_id'],
            'project_id'  => $data['project_id']
        ]);

        $this->tokenService->generateTokensForInvestment($investment, $data['token_quantity']);

        return redirect()->route('investments.index')
            ->with('success', 'Investment created and tokens generated successfully!');
    }

    public function edit(int $id): View
    {
        $investment = Investment::findOrFail($id);
        return view('investments.edit', compact('investment'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'project_id'  => 'required|exists:projects,id',
        ]);

        $investment = Investment::findOrFail($id);
        $investment->update($data);

        return redirect()->route('investments.show', $id)
            ->with('success', 'Investment updated successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $investment = Investment::findOrFail($id);
        $investment->delete();

        return redirect()->route('investments.index')
            ->with('success', 'Investment deleted successfully!');
    }

    public function transfer(Request $request, int $tokenId): RedirectResponse
    {
        $data = $request->validate([
            'new_investment_id' => 'required|exists:investments,id'
        ]);

        $token = Token::findOrFail($tokenId);
        $newInvestment = Investment::findOrFail($data['new_investment_id']);

        $this->tokenService->transferToken($token, $newInvestment);

        return redirect()->back()
            ->with('success', 'Token transferred successfully!');
    }
}
