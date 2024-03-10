<?php

namespace App\Http\Controllers;

use App\Services\IaCredentialService;
use Illuminate\Http\Request;

class iaCredentialController extends Controller
{
    //
    protected $iaCredentialService;

    public function __construct(IaCredentialService $credential)
    {
        $this->iaCredentialService=$credential;
    }


    public function insertCredential(Request $request){
        $this->iaCredentialService->insertToken($request);

        return redirect()->route('tokenInserted');
    }

    public function deleteCredential($id){
        $removed_token=$this->iaCredentialService->removeToken($id);
    }
}
