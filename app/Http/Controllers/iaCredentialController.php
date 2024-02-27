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
        $this->iaCredentialService->insertToken($request->token);

        return redirect()->route('tokenInserted');
    }
}
