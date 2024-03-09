<?php

namespace App\Services;

use App\Models\Ia_credential;

class IaCredentialService{

    public function insertToken($data){
        $retrieve_token=Ia_credential::where('open_ai',$data)->get();
        if(!empty($retrieve_token)){
            $token=Ia_credential::create([
                'open_ai'=>$data
            ]);
        }else{
            $new_token=Ia_credential::find($retrieve_token->id);
            $new_token->open_ai=$data;
            $new_token->save();
        }

    }
}