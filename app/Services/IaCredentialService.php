<?php

namespace App\Services;

use App\Models\Ia_credential;

class IaCredentialService{

    public function insertToken($data){
        $token=Ia_credential::create([
            'open_ai'=>$data
        ]);
    }
}