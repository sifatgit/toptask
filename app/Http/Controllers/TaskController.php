<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Store a new task for the given project (project owner only).
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        // Adding a task modifies the project, so we treat it like an "update" permission.
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'integer', 'min:1', 'max:9999'],
        ]);

        $exists = auth()->user()->tasks()
            ->where('project_id', $project->id)
            ->where('priority', $validated['priority'])
            ->exists();

        if ($exists) {
            return back()->with('warning', 'Task with same priority already exists!');
        }

        auth()->user()->tasks()->create([
            'project_id' => $project->id,
            'name' => $validated['name'],
            'priority' => $validated['priority'],
        ]);

        return back()->with('success', 'Task added successfully!');
    }

    /**
     * Update a task (task owner only).
     * If another task in the same project already has the requested priority, swap priorities safely.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'integer', 'min:1', 'max:9999'],
        ]);

        DB::transaction(function () use ($task, $validated): void {
            $other = auth()->user()->tasks()
                ->where('project_id', $task->project_id)
                ->where('priority', $validated['priority'])
                ->whereKeyNot($task->id)
                ->first();

            if ($other) {
                $other->update(['priority' => $task->priority]);
            }

            $task->update([
                'name' => $validated['name'],
                'priority' => $validated['priority'],
            ]);
        });

        return back()->with('success', 'Task updated successfully!');
    }

    /**
     * Delete a task (task owner only).
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return back()->with('success', 'Task deleted successfully!');
    }
}
