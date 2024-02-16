<?php

namespace App\Services;

use App\Models\Editor;


class EditorService{
    
    
    public function insertEditor($data){
        $new_editor=Editor::create(
            [
                'name'=>$data->name,
                'surname'=>$data->surname,
                'cpf'=>$data->cpf,
                'cnpj'=>$data->cnpj,
                'email'=>$data->email,
                'nickname'=>$data->nickname,
                'password'=>md5($data->password),
                'is_admin'=>$data->has('isAdmin')?1:0,
    
            ]
        );
    
        return $new_editor;
    }
}