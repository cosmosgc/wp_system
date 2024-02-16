<?php

namespace App\Services;

use App\Models\Editor;

class LoginService{
    
    public function verifyCredentials($data){
        $editor =Editor::where('name',$data->name)->first();

        if($editor===md5($data->password)){
            redirect('dashboard');
        }

    }
}