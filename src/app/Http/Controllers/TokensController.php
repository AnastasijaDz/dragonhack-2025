<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

/**
 * Class TokensController
 *
 * This controller handles CRUD operations for tokens. It provides methods to list all tokens,
 * display a specific token, create new tokens, update existing ones, and delete tokens.
 */
class TokensController extends Controller
{
    /**
     * List all tokens.
     *
     * Retrieves all token records from the database and passes them to the 'tokens.index' view.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\View\View The view that displays the list of tokens.
     */
    public function index(Request $request)
    {
        // Retrieve all tokens from the database.
        $tokens = Token::all();

        // Render the 'tokens.index' view, passing the tokens data.
        return view('tokens.index', compact('tokens'));
    }

    /**
     * Show a single token by its ID.
     *
     * Retrieves a token record by its ID and displays its details using the 'tokens.show' view.
     *
     * @param int $id The unique identifier of the token.
     * @return \Illuminate\View\View The view displaying the token details.
     */
    public function show($id)
    {
        // Retrieve the token by ID or abort with a 404 error if not found.
        $token = Token::findOrFail($id);

        // Render the 'tokens.show' view with the token data.
        return view('tokens.show', compact('token'));
    }

    /**
     * Display the form for creating a new token.
     *
     * Renders the 'tokens.create' view which provides a form for entering token information.
     *
     * @return \Illuminate\View\View The view containing the token creation form.
     */
    public function create()
    {
        // Render the view with the form to create a new token.
        return view('tokens.create');
    }

    /**
     * Process the submission and store a new token.
     *
     * Validates the incoming request data, creates a new token record,
     * and then redirects to the token list with a success message.
     *
     * @param Request $request The HTTP request containing the new token data.
     * @return \Illuminate\Http\RedirectResponse Redirects back to the tokens index view.
     */
    public function store(Request $request)
    {
        // Validate the incoming data.
        $data = $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'key'           => 'required|string|max:255'
        ]);

        // Create a new token record using the validated data.
        Token::create($data);

        // Redirect to the tokens index with a success message.
        return redirect()->route('tokens.index')
            ->with('success', 'Token created successfully!');
    }

    /**
     * Display the form for editing an existing token.
     *
     * Retrieves the token record by its ID and renders the 'tokens.edit' view with pre-filled data.
     *
     * @param int $id The unique identifier of the token to edit.
     * @return \Illuminate\View\View The view containing the token edit form.
     */
    public function edit($id)
    {
        // Retrieve the token record by ID or abort with a 404 error if not found.
        $token = Token::findOrFail($id);

        // Render the edit view with the existing token data.
        return view('tokens.edit', compact('token'));
    }

    /**
     * Process the update of a token record.
     *
     * Validates the incoming update data, updates the token record in the database,
     * and redirects to the token details view with a success message.
     *
     * @param Request $request The HTTP request containing updated token data.
     * @param int $id The unique identifier of the token to update.
     * @return \Illuminate\Http\RedirectResponse The redirect response to the token details view.
     */
    public function update(Request $request, $id)
    {
        // Validate the updated token data.
        $data = $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'key'           => 'required|string|max:255'
        ]);

        // Retrieve the token record by its ID.
        $token = Token::findOrFail($id);
        // Update the token record with the validated data.
        $token->update($data);

        // Redirect to the token details view with a success message.
        return redirect()->route('tokens.show', $id)
            ->with('success', 'Token updated successfully!');
    }

    /**
     * Delete a token record.
     *
     * Retrieves a token by its ID, deletes it from the database,
     * and then redirects back to the tokens index with a success message.
     *
     * @param int $id The unique identifier of the token to delete.
     * @return \Illuminate\Http\RedirectResponse The redirect response to the tokens index view.
     */
    public function destroy($id)
    {
        // Retrieve the token record by its ID.
        $token = Token::findOrFail($id);
        // Delete the token from the database.
        $token->delete();

        // Redirect to the tokens index with a success message.
        return redirect()->route('tokens.index')
            ->with('success', 'Token deleted successfully!');
    }
}
