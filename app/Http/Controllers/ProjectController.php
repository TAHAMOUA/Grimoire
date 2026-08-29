<?php

namespace App\Http\Controllers;

use App\Events\MembreAjouteAuProjet;
use App\Events\ProjetCloture;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Afficher uniquement les projets auxquels l'utilisateur appartient.
     * ✅ with('users') évite le N+1 lors de l'affichage des rôles.
     */
    public function index()
    {
        $this->authorize('viewAny', Project::class);/* This is a Policy authorization method.
        It asks:  "Is this user allowed to view projects?"     authorize($ability, $arguments)  */


        $projects = auth()->user()
            ->projects()
            ->with('users')   // ✅ eager load — évite N+1 pour userRole(), Without eager loading, you can create the famous N+1 query problem for example
            // if u have 10 projects without eager loading u made a 11 queries instead of 2 queries if u use it.
            ->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Formulaire de création — tout utilisateur connecté peut créer un projet.
     */
    public function create()
    {
         // Un utilisateur ayant le rôle global "responsable" peut créer un projet.
        $this->authorize('create', Project::class);

        return view('projects.create');
    }

    /**
     * Créer le projet et attacher l'auteur comme responsable.
     */
    public function store(StoreProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        $project = Project::create($request->validated());

/* $project->fill($data);      ➡️ modifies model but doesn't save.
$project->save();           ➡️ saves.
But:Project::create($data); ➡️ creates + saves.

 // Attacher l'auteur en tant que responsable */
        $project->users()->attach(auth()->id(), ['role' => 'responsable']);

/*         User #5 is a member of Project #10 with role responsable.

        attach() is a method on the belongsToMany relationship.
        It adds a record to the pivot table (project_user) with the given user_id and role. 

 */            /*is additional pivot data.
            So Laravel inserts something like:

            INSERT INTO project_user
            (project_id, user_id, role)
            VALUES
            (10, 5, 'responsable'); */

        return redirect()
            ->route('projects.index')
            ->with('success', 'Projet créé avec succès.');
    }

    /**
     * Afficher les détails d'un projet.
     * ✅ with('users') sur la relation pour éviter N+1 dans la vue.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);

        // ✅ Eager load des membres (évite N+1 dans la vue Blade)
        $project->load('users');

                   /*  with() */
        /* "I know I need users when I query."

                    load()
        "I already have the project; now load its users." */

        $userRole = $project->userRole(auth()->user());
        $allUsers = User::whereNotIn('id', $project->users->pluck('id') )->get(); /* pluck() extracts one attribute from a collection. */

        return view('projects.show', compact('project', 'userRole', 'allUsers'));
    }

    /**
     * Formulaire de modification — Responsable seulement.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Mettre à jour le projet — Responsable seulement.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $project->update($request->validated());

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Projet mis à jour avec succès.');
    }

    /**
     * Mettre à jour uniquement l'avancement — Responsable ou Chercheur.
     */
    public function updateAvancement(Request $request, Project $project)
    {
        $this->authorize('updateAvancement', $project);

        $request->validate([
            'avancement' => 'required|integer|min:0|max:100',
        ]);

        $project->update(['avancement' => $request->avancement]);

        return back()->with('success', 'Avancement mis à jour.');
    }

    /**
     * Ajouter un membre au projet — Responsable seulement.
     * ✅ L'event transporte maintenant le rôle pour la notification.
     */
    public function addMember(Request $request, Project $project)
    {
        $this->authorize('manageMember', $project);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role'    => 'required|in:chercheur,etudiant_assistant',
        ]);

        // Vérifier que l'utilisateur n'est pas déjà membre
        if ($project->users()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'Cet utilisateur est déjà membre du projet.');
        }

        $project->users()->syncWithoutDetaching([
            $request->user_id => ['role' => $request->role],
        ]);

        $user = User::findOrFail($request->user_id);

        // ✅ On passe le rôle à l'event pour que la notification soit complète
        event(new MembreAjouteAuProjet($project, $user, $request->role));

        return back()->with('success', 'Membre ajouté.');
    }

    /**
     * Retirer un membre — Responsable seulement.
     * Protection : on ne peut pas retirer le dernier responsable.
     */
    public function removeMember(Project $project, User $user)
    {
        $this->authorize('manageMember', $project);

        // Empêcher la suppression du dernier responsable
        if ($project->isResponsable($user) && !$project->hasOtherResponsable($user)) {
            return back()->with('error', 'Impossible de retirer le dernier responsable du projet.');
        }

        $project->users()->detach($user->id);

        return back()->with('success', 'Membre retiré.');
    }

    /**
     * Projets archivés de l'utilisateur connecté.
     * ✅ with('users') pour éviter N+1 sur les données de l'archive.
     */
    public function archived()
    {
        $this->authorize('viewAny', Project::class);

        $projects = Project::onlyTrashed()
            ->whereHas('users', fn($q) => $q->where('user_id', auth()->id()))
            ->with('users')   // ✅ eager load
            ->get();

        return view('projects.archived', compact('projects'));
    }

    /**
     * Archiver un projet — Responsable seulement.
     * ✅ On charge les membres AVANT le soft-delete pour que le listener puisse les notifier.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete(); // Ceci déclenchera l'événement deleted du modèle (qui génère le rapport et la notif)

        return redirect()
            ->route('projects.index')
            ->with('success', 'Projet archivé avec succès.');
    }
}