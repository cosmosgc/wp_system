<?php

namespace App\Services;

use App\Models\Drive_credential;

class GoogleDriveService{

    public function insertDriveCredentials($data){
        $google_credentials= Drive_credential::create([
            'client_id'=>$data->client_id,
            'project_id'=>$data->project_id,
            'auth_uri'=>$data->auth_uri,
            'token_uri'=>$data->token_uri,
            'auth_provider_x509_cert_url'=>$data->auth_provider_x509_cert_url,
            'client_secret'=>$data->client_secret,
            'redirect_uris'=>$data->redirect_uris,
            'api_key'=>$data->api_key,
            'Editor_id'=>$data->editor_id,
        ]);
        return $google_credentials;
    }

    public function updateCredential($requestData) {
        $google_credentials = Drive_credential::all()->first();

        $data = $requestData->only([
            'client_id',
            'project_id',
            'token_uri',
            'auth_provider_x509_cert_url',
            'client_secret',
            'redirect_uris',
            'api_key'
        ]);

        $google_credentials->update($data);

        return $google_credentials;
    }

}
