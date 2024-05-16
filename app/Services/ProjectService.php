<?php
namespace App\Services;
use App\Models\Project;


class ProjectService{
    
    public function createProject($data){
        $prefix=0;
        if(!empty(Project::all())){
            $prefix=Project::count();
        }
        $prefix++;
        $new_project=Project::create([
            'project_name'=>$prefix.'-'.$data->project_name,
        ]);

    }

}


