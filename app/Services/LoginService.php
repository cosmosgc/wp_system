<?php

namespace App\Services;

use App\Models\Editor;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;

class LoginService{
    
    public function verifyCredentials($data){
        $test = false;
        if($test){
            Cookie::queue('Editor', base64_encode($data->name.'+'.$data->password), 60);
            return 200;
        }
        $editor =Editor::where('name',$data->name)->first();

        if($editor->password===md5($data->password)){
            Cookie::queue('Editor', base64_encode($editor->name.'+'.$editor->password), 60);
            return 200;
        }else{
            return 401;
        }   

    }
}