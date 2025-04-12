<?php

namespace App\Http\Controllers;

use App\Models\Landlord;
use Illuminate\Http\Request;

class LandlordsController extends Controller
{
    // List all landlords
    public function index(Request $request)
    {
        $landlords = Landlord::all();

        return view('landlords.index', compact('landlords'));
    }

    // Show details for a single landlord
    public function show($id)
    {
        $landlord = Landlord::findOrFail($id);

        return view('landlords.show', compact('landlord'));
    }

    // Display the form to create a new landlord
    public function create()
    {
        return view('landlords.create');
    }

    // Process the submission and store a new landlord record
    public function store(Request $request)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone'     => 'required|string|max:50'
        ]);

        Landlord::create($data);

        return redirect()->route('landlords.index')->with('success', 'Landlord created successfully!');
    }

    // Display the form to edit an existing landlord
    public function edit($id)
    {
        $landlord = Landlord::findOrFail($id);

        return view('landlords.edit', compact('landlord'));
    }

    // Process the update of a landlord record
    public function update(Request $request, $id)
    {
        // Validate the updated data
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone'     => 'required|string|max:50'
        ]);

        $landlord = Landlord::findOrFail($id);
        $landlord->update($data);

        return redirect()->route('landlords.show', $id)->with('success', 'Landlord updated successfully!');
    }

    // Delete a landlord record
    public function destroy($id)
    {
        $landlord = Landlord::findOrFail($id);
        $landlord->delete();

        return redirect()->route('landlords.index')->with('success', 'Landlord deleted successfully!');
    }
}
