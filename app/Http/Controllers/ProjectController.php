<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display the authenticated user's projects.
     */
    public function index(): View
    {
        $projects = auth()
            ->user()
            ->projects()
            ->latest('id')
            ->with('tasks')
            ->paginate(5);

        return view('frontend.projects.index', compact('projects'));
    }

    /**
     * Display a single project (owner-only).
     */
    public function view(Project $project): View
    {
        $this->authorize('view', $project);

        // Order is handled in the relationship (Project::tasks()).
        $project->load('tasks');

        return view('frontend.projects.view', compact('project'));
    }

    /**
     * Store a new project for the authenticated user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        auth()->user()->projects()->create($validated);

        return back()->with('success', 'Project created successfully!');
    }

    /**
     * Update an existing project (owner-only).
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $project->update($validated);

        return back()->with('success', 'Project updated successfully!');
    }

    /**
     * Delete a project (owner-only).
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return back()->with('success', 'Project deleted successfully!');
    }
}
