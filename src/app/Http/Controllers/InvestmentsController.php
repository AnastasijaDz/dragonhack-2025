<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use Illuminate\Http\Request;

class InvestmentsController extends Controller
{
    // List all investments.
    public function index(Request $request)
    {
        $investments = Investment::all();

        return view('investments.index', compact('investments'));
    }

    // Show details for a single investment.
    public function show($id)
    {
        $investment = Investment::findOrFail($id);

        return view('investments.show', compact('investment'));
    }

    // Display the form to create a new investment.
    public function create()
    {
        return view('investments.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data.
        $data = $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'project_id'  => 'required|exists:projects,id',
        ]);

        Investment::create($data);

        return redirect()->route('investments.index')->with('success', 'Investment created successfully!');
    }

    // Display the form to edit an existing investment.
    public function edit($id)
    {
        $investment = Investment::findOrFail($id);

        return view('investments.edit', compact('investment'));
    }

    // Process the update of an investment.
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'projekt_id'  => 'required|exists:projects,id',
        ]);

        $investment = Investment::findOrFail($id);
        $investment->update($data);

        return redirect()->route('investments.show', $id)->with('success', 'Investment updated successfully!');
    }

    // Delete an investment record.
    public function destroy($id)
    {
        $investment = Investment::findOrFail($id);
        $investment->delete();

        return redirect()->route('investments.index')->with('success', 'Investment deleted successfully!');
    }
}
