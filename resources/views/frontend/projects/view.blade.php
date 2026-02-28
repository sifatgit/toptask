<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project view</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1">{{ $project->name }}</h3>
            <div class="text-muted small">Project details & tasks</div>
        </div>
        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">Back to projects</a>
    </div>

    {{-- Alerts --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    {{-- Project card --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong>Project info</strong>
        </div>
        <div class="card-body">
            <div class="mb-2">
                <span class="text-muted">Name:</span>
                <span class="fw-semibold">{{ $project->name }}</span>
            </div>
            <div>
                <span class="text-muted">Description:</span>
                <div class="mt-1">{{ $project->description }}</div>
            </div>
        </div>
    </div>

    {{-- Add task --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong>Add a task</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('tasks.store', $project) }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Task name</label>
                        <input class="form-control" type="text" name="name"
                               value="{{ old('name') }}" placeholder="Enter task name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Priority</label>
                        <input class="form-control" type="number" name="priority"
                               value="{{ old('priority') }}" placeholder="e.g. 1" min="1">
                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-success" type="submit">Add task</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tasks table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Task list</strong>
            <span class="text-muted small">Total: {{ $project->tasks->count() }}</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 55%;">Task</th>
                        <th style="width: 15%;">Priority</th>
                        <th style="width: 30%;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($project->tasks as $task)
                        <tr>
                            <td class="fw-semibold">{{ $task->name }}</td>
                            <td>
                                <span class="badge text-bg-secondary">{{ $task->priority }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-outline-primary btn-sm"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#editTask{{ $task->id }}"
                                            aria-expanded="false"
                                            aria-controls="editTask{{ $task->id }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this task?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                                    </form>
                                </div>

                                {{-- Inline edit form (collapsed) --}}
                                <div class="collapse mt-3" id="editTask{{ $task->id }}">
                                    <div class="border rounded p-3 bg-white">
                                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="row g-2">
                                                <div class="col-md-8">
                                                    <label class="form-label mb-1 small">Task name</label>
                                                    <input class="form-control form-control-sm" type="text" name="name"
                                                           value="{{ old('name', $task->name) }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label mb-1 small">Priority</label>
                                                    <input class="form-control form-control-sm" type="number" name="priority"
                                                           value="{{ old('priority', $task->priority) }}" min="1">
                                                </div>
                                            </div>

                                            <div class="d-flex gap-2 mt-3">
                                                <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                                <button class="btn btn-light btn-sm" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#editTask{{ $task->id }}">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                No tasks yet. Add your first task above.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>		
</body>
</html>