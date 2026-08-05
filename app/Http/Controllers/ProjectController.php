<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\User;
use Illuminate\Http\Request;
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Project::class);

        $projects = Project::all();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Project::class);

        return view('projects.create');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        Project::create($request->validated());

        return redirect()
            ->route('projects.index')
            ->with('success', 'Projet créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $project->update($request->validated());

        return redirect()
            ->route('projects.index')
            ->with('success', 'Projet mis à jour avec succès.');
    }
    public function addMember(Request $request, Project $project)
{
    $this->authorize('update', $project);

    $request->validate([
        'user_id' => 'required|exists:users,id',
        'role' => 'required|in:chercheur,etudiant_assistant',
    ]);

    $project->users()->syncWithoutDetaching([
        $request->user_id => [
            'role' => $request->role
        ]
    ]);

    return back()->with('success', 'Membre ajouté.');
}
public function removeMember(Project $project, User $user)
{
    $this->authorize('delete', $project);

    $project->users()->detach($user->id);

    return back()->with('success', 'Membre retiré.');
}
public function archived()
{
    $projects = Project::onlyTrashed()->get();

    return view('projects.archived', compact('projects'));
}
    /**
     * Remove the specified resource.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Projet archivé avec succès.');
    }
}
