<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projects) {
        $this->projectService=$projects;
    }
    
    public function insertProject(Request $request){
        $new_project=$this->projectService->createProject($request);

        return response()->json(['message' => 'Project successfully created'], 200);

    }
}
