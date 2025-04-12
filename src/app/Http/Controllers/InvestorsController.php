<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use Illuminate\Http\Request;

class InvestorsController extends Controller
{
    // List all investors.
    public function index(Request $request)
    {
        $investors = Investor::all();

        return view('investors.index', compact('investors'));
    }

    // Show details for a single investor.
    public function show($id)
    {
        $investor = Investor::findOrFail($id);

        return view('investors.show', compact('investor'));
    }

    // Display a form to create a new investor.
    public function create()
    {
        return view('investors.create');
    }

    // Process and store a new investor.
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $data = $request->validate([
            'ime'     => 'required|string|max:255',
            'prezime' => 'required|string|max:255',
            'tel'     => 'required|string|max:50',
        ]);

        Investor::create($data);

        return redirect()->route('investors.index')->with('success', 'Investor created successfully!');
    }

    // Display a form to edit an existing investor.
    public function edit($id)
    {
        $investor = Investor::findOrFail($id);

        return view('investors.edit', compact('investor'));
    }

    // Process the update of an investor.
    public function update(Request $request, $id)
    {
        // Validate the update data.
        $data = $request->validate([
            'ime'     => 'required|string|max:255',
            'prezime' => 'required|string|max:255',
            'tel'     => 'required|string|max:50',
        ]);

        $investor = Investor::findOrFail($id);
        $investor->update($data);

        return redirect()->route('investors.show', $id)->with('success', 'Investor updated successfully!');
    }

    // Delete an investor.
    public function destroy($id)
    {
        $investor = Investor::findOrFail($id);
        $investor->delete();

        // Redirect back to the investors list with a success message.
        return redirect()->route('investors.index')->with('success', 'Investor deleted successfully!');
    }
}
