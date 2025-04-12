<?php

namespace App\Http\Controllers;

use App\Models\Landlord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class LandlordsController
 *
 * Handles CRUD (Create, Read, Update, Delete) operations for landlord records.
 * This controller provides methods to list, show, create, edit, update, and delete landlords.
 */
class LandlordsController extends Controller
{
    /**
     * List all landlords.
     *
     * Retrieves all landlord records from the database and renders the 'landlords.index' view.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The view displaying the list of landlords.
     */
    public function index(Request $request)
    {
        // Retrieve all landlord records from the database.
        $landlords = Landlord::all();

        // Render the view with the list of landlords.
        return view('landlords.index', compact('landlords'));
    }

    /**
     * Show details for a single landlord.
     *
     * Finds a landlord by its ID and renders the 'landlords.show' view with the landlord's details.
     *
     * @param int $id The unique identifier of the landlord.
     * @return View The view displaying the landlord's details.
     */
    public function show($id)
    {
        // Retrieve the landlord record or abort with a 404 error if not found.
        $landlord = Landlord::findOrFail($id);

        // Render the view with the landlord's details.
        return view('landlords.show', compact('landlord'));
    }

    /**
     * Display the form to create a new landlord.
     *
     * Renders the 'landlords.create' view to show the landlord creation form.
     *
     * @return View The view containing the form to create a new landlord.
     */
    public function create()
    {
        // Render the view to display the landlord creation form.
        return view('landlords.create');
    }

    /**
     * Process the submission and store a new landlord record.
     *
     * Validates the incoming request data and creates a new landlord.
     * Then redirects to the landlords index view with a success message.
     *
     * @param Request $request The incoming HTTP request containing the new landlord data.
     * @return RedirectResponse Redirect response after storing the new landlord.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:50'
        ]);

        // Create a new landlord record with the validated data.
        Landlord::create($data);

        // Redirect to the landlords index with a success message.
        return redirect()->route('landlords.index')
            ->with('success', 'Landlord created successfully!');
    }

    /**
     * Display the form to edit an existing landlord.
     *
     * Finds the specified landlord by ID and renders the 'landlords.edit' view with pre-filled data.
     *
     * @param int $id The unique identifier of the landlord to edit.
     * @return View The view containing the form to edit the landlord.
     */
    public function edit($id)
    {
        // Retrieve the landlord record or abort with a 404 error if not found.
        $landlord = Landlord::findOrFail($id);

        // Render the view to display the landlord edit form.
        return view('landlords.edit', compact('landlord'));
    }

    /**
     * Process the update of a landlord record.
     *
     * Validates the incoming data and updates the specified landlord record.
     * Redirects to the landlord's detail view with a success message upon completion.
     *
     * @param Request $request The incoming HTTP request with updated landlord data.
     * @param int $id The unique identifier of the landlord to update.
     * @return RedirectResponse Redirect response after updating the landlord.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming update data.
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:50'
        ]);

        // Retrieve the landlord record to update.
        $landlord = Landlord::findOrFail($id);
        // Update the landlord record with the validated data.
        $landlord->update($data);

        // Redirect to the landlord's detail view with a success message.
        return redirect()->route('landlords.show', $id)
            ->with('success', 'Landlord updated successfully!');
    }

    /**
     * Delete a landlord record.
     *
     * Finds and deletes the landlord record by its ID, then redirects back
     * to the landlords index with a success message.
     *
     * @param int $id The unique identifier of the landlord to delete.
     * @return RedirectResponse Redirect response after deletion.
     */
    public function destroy($id)
    {
        // Retrieve the landlord record or abort with a 404 error if not found.
        $landlord = Landlord::findOrFail($id);
        // Delete the landlord record from the database.
        $landlord->delete();

        // Redirect to the landlords index with a success message.
        return redirect()->route('landlords.index')
            ->with('success', 'Landlord deleted successfully!');
    }
}
