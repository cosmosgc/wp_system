<?php

namespace App\Services;

use App\Models\Editor;
use App\Models\Wp_credential;

class SiteCredentialsService{
    public function insertSite($data){

        $editor=Editor::where('name',$data->user)->get();
        $new_credential=Wp_credential::create([
            'wp_login'=>$data->wp_login,
            'wp_password'=>$data->wp_password,
            'wp_domain'=>$data->wp_domain,
            'Editor_id'=>$editor[0]->id,
        ]);
    }
}