<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource; // Utilisation de resources pour structurer la réponse


class ProjectController extends Controller
{

    // Récupérer tous les projets
    public function index()
    {
        $projects = Project::all(); // Récupération de tous les projets
        return ProjectResource::collection($projects); // Utilisation de la resource pour la réponse
    }

    // Créer un nouveau projet
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'activity_id' => 'required|exists:activities,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::create($validated);

        return new ProjectResource($project);
    }

    // Afficher un projet spécifique
    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    // Mettre à jour un projet spécifique
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'activity_id' => 'required|exists:activities,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $project->update($validated);

        return new ProjectResource($project);
    }

    // Supprimer un projet
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Project deleted successfully'], 204);
    }
}
