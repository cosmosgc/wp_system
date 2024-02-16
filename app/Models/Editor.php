<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editor extends Model
{
    use HasFactory;

    protected $fillable=['name','surname','cpf','cnpj','email','nickname','password','is_admin'];

    public function postContents(){
        return $this->hasMany(Wp_post_content::class);
    }

    public function links(){
        return $this->hasMany(Wp_credential::class);
    }
}
