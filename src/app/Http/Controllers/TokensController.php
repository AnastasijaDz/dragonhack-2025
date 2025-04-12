<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

class TokensController extends Controller
{
    // List all tokens
    public function index(Request $request)
    {
        $tokens = Token::all();

        return view('tokens.index', compact('tokens'));
    }

    // Show a single token by its ID.
    public function show($id)
    {
        $token = Token::findOrFail($id);

        return view('tokens.show', compact('token'));
    }

    // Display the form for creating a new token.
    public function create()
    {
        return view('tokens.create');
    }

    // Process the submission and store a new token.
    public function store(Request $request)
    {
        // Validate the incoming data.
        $data = $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'key'     => 'required|string|max:255'
        ]);

        Token::create($data);

        // Redirect to the token list with a success message.
        return redirect()->route('tokens.index')->with('success', 'Token created successfully!');
    }

    // Display the form for editing an existing token.
    public function edit($id)
    {
        $token = Token::findOrFail($id);

        return view('tokens.edit', compact('token'));
    }

    // Process the update of a token record.
    public function update(Request $request, $id)
    {
        // Validate the updated data.
        $data = $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'key'     => 'required|string|max:255'
        ]);

        $token = Token::findOrFail($id);
        $token->update($data);

        return redirect()->route('tokens.show', $id)->with('success', 'Token updated successfully!');
    }

    // Delete a token record.
    public function destroy($id)
    {
        $token = Token::findOrFail($id);
        $token->delete();

        return redirect()->route('tokens.index')->with('success', 'Token deleted successfully!');
    }
}
