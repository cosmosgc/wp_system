<?php

namespace App\Services;

use App\Models\Wp_credential;

class SiteCredentialsService{
    public function insertSite($data){
        $new_credential=Wp_credential::create([
            'wp_login'=>$data->wp_login,
            'wp_password'=>$data->wp_password,
            'wp_domain'=>$data->wp_domain
        ]);
    }
}