<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Routes accessible without authentication.
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Dashboard
|--------------------------------------------------------------------------
| Requires user to be authenticated and email verified.
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Protected Routes (Authentication Required)
|--------------------------------------------------------------------------
| All routes inside this group require a logged-in user.
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Project Management Routes
    |--------------------------------------------------------------------------
    | Handles CRUD operations for projects.
    | Uses route model binding for {project}.
    */
    Route::controller(ProjectController::class)->group(function () {

        Route::get('/projects', 'index')->name('projects.index');       // List all projects
        Route::post('/projects', 'store')->name('projects.store');      // Create new project
        Route::get('/project/{project}', 'view')->name('projects.view'); // View single project
        Route::put('/project/{project}', 'update')->name('projects.update'); // Update project
        Route::delete('/project/{project}', 'destroy')->name('projects.destroy'); // Delete project
    });


    /*
    |--------------------------------------------------------------------------
    | Task Management Routes
    |--------------------------------------------------------------------------
    | Nested task creation under a project.
    | Route model binding used for {project} and {task}.
    */
    Route::controller(TaskController::class)->group(function () {

        Route::post('/projects/{project}/tasks', 'store')->name('tasks.store'); // Add task to project
        Route::put('/tasks/{task}', 'update')->name('tasks.update');            // Update task
        Route::post('/projects/{project}/tasks/reorder', 'reorder')->name('tasks.reorder'); // Reorder tasks via AJAX
        Route::delete('/tasks/{task}', 'destroy')->name('tasks.destroy');       // Delete task
    });


    /*
    |--------------------------------------------------------------------------
    | User Profile Routes
    |--------------------------------------------------------------------------
    | Allows authenticated users to manage their profile.
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| Default Laravel Breeze authentication routes.
*/
require __DIR__ . '/auth.php';