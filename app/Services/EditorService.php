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

    public function updateEditor($data){
        $editor=Editor::find(intval($data->id));

        $editor->name=$data->name;
        $editor->surname=$data->surname;
        $editor->cpf=$data->cpf;
        $editor->cnpj=$data->cnpj;
        $editor->email=$data->email;
        $editor->password=md5($data->password);
        $editor->is_admin=$data->has('is_admin')?1:0;

        $editor->save();

        return $editor;
    }
}