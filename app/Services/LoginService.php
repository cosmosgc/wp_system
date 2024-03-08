<?php

namespace App\Services;

use App\Models\Editor;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;

class LoginService{
    
    public function verifyCredentials($data){

        $editor =Editor::where('name',$data->name)->first();

        if($editor->password===md5($data->password)){
            Cookie::queue('editor', base64_encode($editor->name.'+'.$editor->password), 60);
            return 200;
        }else{
            return 401;
        }
    }
    public function forgetCookie($data){
        Cookie::queue(Cookie::forget($data));
    }
}