<?php

namespace App\Services;

use App\Models\Editor;
use App\Models\Ia_credential;

class IaCredentialService{

    public function insertToken($data){
        $retrieve_user=Editor::where('name',$data->editor)->get();
        $retrieve_token=Ia_credential::where('Editor_id',$retrieve_user[0]->id)->get();
        if(empty($retrieve_token)){
            $token=Ia_credential::create([
                'open_ai'=>$data->token,
                'language'=>$data->language,
                'writing_style'=>$data->writing_style,
                'writing_tone'=>$data->writing_tone,
                'sections'=>$data->sections,
                'pagraphs'=>$data->paragraphs,
                'Editor_id'=>$retrieve_user[0]->id
            ]);
        }else{
            $new_token=Ia_credential::find($retrieve_token[0]->id);
            $new_token->open_ai=$data->token;
            $new_token->save();
        }

    }

    public function removeToken($id){
        $token=Ia_credential::find(intval($id));
        $token->delete();
    }

}