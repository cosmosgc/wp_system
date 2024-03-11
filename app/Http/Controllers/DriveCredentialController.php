<?php

namespace App\Http\Controllers;

use GoogleDriveService;
use Illuminate\Http\Request;

class DriveCredentialController extends Controller
{
    //
    protected $google_credentials;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->google_credentials=$driveService;
    }


    public function insertGoogleCredentials(Request $request){
        $new_g_credential=$this->google_credentials->insertDriveCredentials($request);
    }


    public function updateGoogleCredentials(Request $request){
        $update_credential=$this->google_credentials->updateCredential($request);
    }
}
