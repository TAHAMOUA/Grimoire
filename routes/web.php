<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Page d'accueil
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Routes protégées
|--------------------------------------------------------------------------
*/


/* Route::get('/flights', function () {
    // Only authenticated users may access this route...
})->middleware('auth'); */


Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Projets
    |--------------------------------------------------------------------------
    |
    | IMPORTANT : la route 'archived' doit être AVANT la resource pour que
    | Laravel ne l'interprète pas comme show(id='archived').
    |
    */

    // Projets archivés — avant le resource pour éviter le conflit
    Route::get('/projects/archived', [ProjectController::class, 'archived'])
        ->name('projects.archived');

    // CRUD standard
    Route::resource('projects', ProjectController::class)->withTrashed(['show']);

    // Mettre à jour l'avancement (Responsable ou Chercheur)
    Route::patch('/projects/{project}/avancement', [ProjectController::class, 'updateAvancement'])
        ->name('projects.avancement.update');

    // Gestion des membres (Responsable seulement)
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])
        ->name('projects.members.add');

    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])
        ->name('projects.members.remove');

    // Notifications
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

require __DIR__.'/auth.php';