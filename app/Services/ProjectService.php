<?php
namespace App\Services;
use App\Models\Project;


class ProjectService{
    
    public function createProject($data){
        $new_project=Project::create([
            'project_name'=>$data->index.'-'.$data->project_name,
        ]);

    }

}


