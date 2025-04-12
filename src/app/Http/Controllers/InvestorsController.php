<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class InvestorsController
 *
 * This controller handles all operations related to investors,
 * including listing, viewing, creating, updating, and deleting investor records.
 */
class InvestorsController extends Controller
{
    /**
     * List all investors.
     *
     * Retrieves all investor records and renders the 'investors.index' view.
     *
     * @param Request $request The HTTP request instance.
     * @return View
     */
    public function index(Request $request)
    {
        // Retrieve all investors from the database.
        $investors = Investor::all();

        // Render the view with the retrieved investors.
        return view('investors.index', compact('investors'));
    }

    /**
     * Show details for a single investor.
     *
     * Finds an investor by its ID and displays their details.
     *
     * @param int $id The ID of the investor.
     * @return View
     */
    public function show($id)
    {
        // Retrieve investor by ID or throw a 404 error if not found.
        $investor = Investor::findOrFail($id);

        // Render the view with the investor's details.
        return view('investors.show', compact('investor'));
    }

    /**
     * Display the form to create a new investor.
     *
     * @return View
     */
    public function create()
    {
        // Render the investor creation form view.
        return view('investors.create');
    }

    /**
     * Process and store a new investor.
     *
     * Validates input data and creates a new investor record.
     *
     * @param Request $request The HTTP request instance.
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $data = $request->validate([
            'ime'     => 'required|string|max:255',
            'prezime' => 'required|string|max:255',
            'tel'     => 'required|string|max:50',
        ]);

        // Create a new investor record with the validated data.
        Investor::create($data);

        // Redirect to the investors index with a success message.
        return redirect()->route('investors.index')
            ->with('success', 'Investor created successfully!');
    }

    /**
     * Display the form to edit an existing investor.
     *
     * Finds the investor by its ID and renders the edit form.
     *
     * @param int $id The ID of the investor to edit.
     * @return View
     */
    public function edit($id)
    {
        // Retrieve investor record or throw a 404 error if not found.
        $investor = Investor::findOrFail($id);

        // Render the view with the investor's data pre-filled.
        return view('investors.edit', compact('investor'));
    }

    /**
     * Process the update of an investor.
     *
     * Validates the incoming data and updates the corresponding investor record.
     *
     * @param Request $request The HTTP request instance.
     * @param int $id The ID of the investor to update.
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the update data.
        $data = $request->validate([
            'ime'     => 'required|string|max:255',
            'prezime' => 'required|string|max:255',
            'tel'     => 'required|string|max:50',
        ]);

        // Retrieve investor record or throw a 404 error if not found.
        $investor = Investor::findOrFail($id);

        // Update the investor record with the validated data.
        $investor->update($data);

        // Redirect to the investor's details view with a success message.
        return redirect()->route('investors.show', $id)
            ->with('success', 'Investor updated successfully!');
    }

    /**
     * Delete an investor.
     *
     * Finds and deletes an investor record by its ID.
     *
     * @param int $id The ID of the investor to delete.
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        // Retrieve investor record by ID or throw a 404 error if not found.
        $investor = Investor::findOrFail($id);

        // Delete the investor record from the database.
        $investor->delete();

        // Redirect to the investors index with a success message.
        return redirect()->route('investors.index')
            ->with('success', 'Investor deleted successfully!');
    }
}
