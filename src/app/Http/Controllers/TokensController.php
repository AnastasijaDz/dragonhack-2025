<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TokensController extends Controller
{
    public function index(Request $request): View
    {
        $tokens = Token::all();
        return view('tokens.index', compact('tokens'));
    }

    public function show(int $id): View
    {
        $token = Token::findOrFail($id);
        return view('tokens.show', compact('token'));
    }

    public function create(): View
    {
        return view('tokens.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'key'           => 'required|string|max:255'
        ]);

        Token::create($data);

        return redirect()->route('tokens.index')
            ->with('success', 'Token created successfully!');
    }

    public function edit(int $id): View
    {
        $token = Token::findOrFail($id);
        return view('tokens.edit', compact('token'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'key'           => 'required|string|max:255'
        ]);

        $token = Token::findOrFail($id);
        $token->update($data);

        return redirect()->route('tokens.show', $id)
            ->with('success', 'Token updated successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $token = Token::findOrFail($id);
        $token->delete();

        return redirect()->route('tokens.index')
            ->with('success', 'Token deleted successfully!');
    }
}
