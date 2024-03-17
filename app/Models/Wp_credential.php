<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Wp_credential extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable=['wp_login','wp_password','wp_domain','Editor_id'];

    public function editor(){
        return $this->belongsTo(Editor::class);
    }


    public function content(){
        return $this->hasMany(Wp_post_content::class);
    }
}
