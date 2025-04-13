<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InvestorsController extends Controller
{
    public function index(Request $request): View
    {
        $investors = Investor::all();
        return view('investors.index', compact('investors'));
    }

    public function show(): View
    {
        $user = Auth::user();
        $investor = Investor::with('user')->findOrFail($user->profile()->id);
        // dd($investor->total_invested); // Invested
        // dd($investor->earned_price); // Earned back
        // dd($investor->most_profitable_project?->name); // Most profitable project
        // dd($investor->investments_per_year); // Most profitable project
        // dd($investor->investment_allocation); // Investment allocation
        // dd($investor->getInvestmentYearsAttribute()); // Investment allocation
        // dd($investor->getAnnualRateOfReturnAttribute()); // procent
        return view('investors.show', compact('investor'));
    }

    public function create(): View
    {
        return view('investors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ime'     => 'required|string|max:255',
            'prezime' => 'required|string|max:255',
            'tel'     => 'required|string|max:50',
        ]);

        Investor::create($data);

        return redirect()->route('investors.index')
            ->with('success', 'Investor created successfully!');
    }

    public function edit(int $id): View
    {
        $investor = Investor::findOrFail($id);
        return view('investors.edit', compact('investor'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'ime'     => 'required|string|max:255',
            'prezime' => 'required|string|max:255',
            'tel'     => 'required|string|max:50',
        ]);

        $investor = Investor::findOrFail($id);
        $investor->update($data);

        return redirect()->route('investors.show', $id)
            ->with('success', 'Investor updated successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $investor = Investor::findOrFail($id);
        $investor->delete();

        return redirect()->route('investors.index')
            ->with('success', 'Investor deleted successfully!');
    }
}
