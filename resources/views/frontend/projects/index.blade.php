<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Projects</h3>
        <div class="text-muted small">Signed in as: {{ auth()->user()->name }}</div>
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

    {{-- Create Project --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong>Create a new project</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Project name</label>
                    <input class="form-control" type="text" name="name" placeholder="Enter project name"
                           value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Project description</label>
                    <textarea class="form-control" name="description" rows="3"
                              placeholder="Enter project description">{{ old('description') }}</textarea>
                </div>

                <button class="btn btn-success" type="submit">Create</button>
            </form>
        </div>
    </div>

    {{-- Projects Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>All projects</strong>
            <span class="text-muted small">Total: {{ $projects->total() }}</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 20%;">Name</th>
                        <th>Description</th>
                        <th style="width: 28%;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td class="fw-semibold">{{ $project->name }}</td>
                            <td class="text-muted">{{ $project->description }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a class="btn btn-outline-secondary btn-sm"
                                       href="{{ route('projects.view', $project) }}">
                                        View
                                    </a>

                                    <button class="btn btn-outline-primary btn-sm"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#editProject{{ $project->id }}"
                                            aria-expanded="false"
                                            aria-controls="editProject{{ $project->id }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this project?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                                    </form>
                                </div>

                                {{-- Inline edit form (collapsed) --}}
                                <div class="collapse mt-3" id="editProject{{ $project->id }}">
                                    <div class="border rounded p-3 bg-white">
                                        <form action="{{ route('projects.update', $project) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-2">
                                                <label class="form-label mb-1 small">Name</label>
                                                <input class="form-control form-control-sm" type="text" name="name"
                                                       value="{{ old('name', $project->name) }}">
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label mb-1 small">Description</label>
                                                <textarea class="form-control form-control-sm" name="description" rows="2">{{ old('description', $project->description) }}</textarea>
                                            </div>

                                            <div class="d-flex gap-2">
                                                <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                                <button class="btn btn-light btn-sm" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#editProject{{ $project->id }}">
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
                                No projects found. Create your first one above.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($projects, 'links'))
            <div class="card-footer bg-white">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>		
</body>
</html>