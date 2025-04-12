<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProjectsController
 *
 * This controller handles CRUD operations for projects. It provides methods
 * to list all projects, display a specific project, create new projects, update
 * existing ones, and delete projects.
 */
class ProjectsController extends Controller
{
    /**
     * List all projects.
     *
     * Retrieves all project records from the database and passes them to the
     * 'projects.index' view.
     *
     * @param Request $request The HTTP request instance.
     * @return View The view displaying the list of projects.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        // Retrieve all projects from the database.
        $projects = Project::all();

        // Render the projects index view with retrieved projects.
        return view('projects.index', compact('projects'));
    }

    /**
     * Show a single project by its ID.
     *
     * Retrieves a project record by its ID and displays its details using
     * the 'projects.show' view.
     *
     * @param int $id The unique identifier of the project.
     * @return View The view displaying the project details.
     */
    public function show($id)
    {
        // Retrieve the project by ID or throw a 404 error if not found.
        $project = Project::findOrFail($id);

        // Render the project details view with the project data.
        return view('projects.show', compact('project'));
    }

    /**
     * Display the form for creating a new project.
     *
     * Renders the 'projects.create' view to allow the user to fill out the form.
     *
     * @return View The view containing the project creation form.
     */
    public function create()
    {
        // Render the project creation form view.
        return view('projects.create');
    }

    /**
     * Process the submission of a new project.
     *
     * Validates the incoming request data, creates a new project record,
     * and redirects to the projects index with a success message.
     *
     * @param Request $request The HTTP request containing the new project data.
     * @return RedirectResponse The redirect response to the project index view.
     */
    public function store(Request $request)
    {
        // Validate the incoming data.
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'amount'      => 'required|integer'
        ]);

        // Create a new project record using the validated data.
        Project::create($data);

        // Redirect to the projects index with a success message.
        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the form for editing an existing project.
     *
     * Retrieves the specified project by its ID and renders the 'projects.edit'
     * view with the project data to pre-fill the form.
     *
     * @param int $id The unique identifier of the project to edit.
     * @return View The view containing the project edit form.
     */
    public function edit($id)
    {
        // Retrieve the project record by its ID.
        $project = Project::findOrFail($id);

        // Render the project edit view with existing project data.
        return view('projects.edit', compact('project'));
    }

    /**
     * Process the update of a project.
     *
     * Validates the updated request data, updates the project record in the database,
     * and redirects to the project details view with a success message.
     *
     * @param Request $request The HTTP request containing the updated project data.
     * @param int $id The unique identifier of the project to update.
     * @return RedirectResponse The redirect response to the project details view.
     */
    public function update(Request $request, $id)
    {
        // Validate the updated project data.
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'amount'      => 'required|integer'
        ]);

        // Retrieve the project record by its ID.
        $project = Project::findOrFail($id);
        // Update the project record with the validated data.
        $project->update($data);

        // Redirect to the project details page with a success message.
        return redirect()->route('projects.show', $id)
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Delete a project.
     *
     * Retrieves the specified project by its ID, deletes it from the database,
     * and then redirects back to the projects index with a success message.
     *
     * @param int $id The unique identifier of the project to delete.
     * @return RedirectResponse The redirect response to the project index view.
     */
    public function destroy($id)
    {
        // Retrieve the project record by its ID.
        $project = Project::findOrFail($id);
        // Delete the project record from the database.
        $project->delete();

        // Redirect to the projects index with a success message.
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}
