<?php

namespace App\Http\Controllers;

use App\Services\SiteCredentialsService;
use Illuminate\Http\Request;

class WpCredentialController extends Controller
{
    //
    protected $wp_credential_service;

    public function __construct(SiteCredentialsService $credential_service)
    {
        $this->wp_credential_service=$credential_service;
        
    }


    public function saveWpCredential(Request $request){
        $new_credential=$this->wp_credential_service->insertSite($request);
        return redirect()->route('credentialCreated');
    }

    public function updateWpCredential(Request $request){
        $new_credential_data=$this->wp_credential_service->updateSite($request);
    }

    public function deleteWpCredential($id){
        $credential_deletion=$this->wp_credential_service->deleteCredential($id);
        return view('dashboard.documentDeleted');
    }
}
