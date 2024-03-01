<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wp_credential extends Model
{
    use HasFactory;

    public function editor(){
        return $this->belongsTo(Editor::class);
    }


    public function content(){
        return $this->hasMany(Wp_post_content::class);
    }
}
