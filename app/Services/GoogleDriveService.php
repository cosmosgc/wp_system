<?php

namespace App\Services;

use App\Models\Drive_credential;

class GoogleDriveService{

    public function insertDriveCredentials($data){
        // dd($data->request);
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

    public function updateCredential($data){
        $new_google_credentials=Drive_credential::find($data->id);

        $new_google_credentials->client_id=$data->client_id;
        $new_google_credentials->project_id=$data->project_id;
        $new_google_credentials->token_uri=$data->token_uri;
        $new_google_credentials->auth_provider_x509_cert_url=$data->auth_provider_x509_cert_url;
        $new_google_credentials->client_secret=$data->client_secret;
        $new_google_credentials->redirect_uris=$data->redirect_uris;
        $new_google_credentials->api_key=$data->api_key;
        $new_google_credentials->save();
    }
}
