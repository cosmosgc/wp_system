<?php

namespace App\Http\Controllers;

use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class loginController extends Controller
{
    //
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService=$loginService;
    }


    public function validateLogin(Request $request){
        $data = (object) $request->only(['name', 'password']);
        $login=$this->loginService->verifyCredentials($data);
        if($login==200){
            return response(null, Response::HTTP_OK);;
        }else{
            return response(null, Response::HTTP_UNAUTHORIZED);
        }
    }
}
