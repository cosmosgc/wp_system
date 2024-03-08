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

    public function updateSite($data){
        $updated_credentials=Wp_credential::find(intval($data->id));

        $updated_credentials->wp_login=$data->login;
        $updated_credentials->wp_password=$data->password;
        $updated_credentials->wp_domain=$data->domain;

        $updated_credentials->save();
    }

    public function deleteCredential($data){
        $credential=Wp_credential::find(intval($data));
        $credential->delete();
    }

}