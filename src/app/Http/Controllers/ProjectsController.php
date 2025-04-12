<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    // List all projects
    public function index(Request $request)
    {
        $projects = Project::all();

        return view('projects.index', compact('projects'));
    }

    // Show a single project by its ID.
    public function show($id)
    {
        $project = Project::findOrFail($id);

        return view('projects.show', compact('project'));
    }

    // Display the form for creating a new project.
    public function create()
    {
        return view('projects.create');
    }

    // Process the submission of a new project.
    public function store(Request $request)
    {
        // Validate incoming data
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'        => 'required|numeric',
            'amount'    => 'required|integer'
        ]);

        Project::create($data);

        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }

    // Display the form for editing an existing project.
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        return view('projects.edit', compact('project'));
    }

    // Process the update of a project.
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'        => 'required|numeric',
            'amount'    => 'required|integer'
        ]);

        $project = Project::findOrFail($id);
        $project->update($data);

        // Redirect to the project details page with a success message.
        return redirect()->route('projects.show', $id)->with('success', 'Project updated successfully!');
    }

    // Delete a project.
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
    }
}
