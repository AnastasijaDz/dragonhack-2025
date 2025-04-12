<?php

namespace App\Http\Controllers;

use App\Models\Landlord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandlordsController extends Controller
{
    public function index(Request $request): View
    {
        $landlords = Landlord::all();
        return view('landlords.index', compact('landlords'));
    }

    public function show(int $id): View
    {
        $landlord = Landlord::findOrFail($id);
        return view('landlords.show', compact('landlord'));
    }

    public function create(): View
    {
        return view('landlords.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:50'
        ]);

        Landlord::create($data);

        return redirect()->route('landlords.index')
            ->with('success', 'Landlord created successfully!');
    }

    public function edit(int $id): View
    {
        $landlord = Landlord::findOrFail($id);
        return view('landlords.edit', compact('landlord'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:50'
        ]);

        $landlord = Landlord::findOrFail($id);
        $landlord->update($data);

        return redirect()->route('landlords.show', $id)
            ->with('success', 'Landlord updated successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $landlord = Landlord::findOrFail($id);
        $landlord->delete();

        return redirect()->route('landlords.index')
            ->with('success', 'Landlord deleted successfully!');
    }
}
