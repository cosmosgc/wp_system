<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Wp_post_content extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable=['theme','keyword','Editor_id','Project_id','category','anchor_1','url_link_1','url_link_2','do_follow_link_1','anchor_2','do_follow_link_2','anchor_3','url_link_3','do_follow_link_3','post_image','internal_link','post_content','status','schedule_date','insert_image','domain', 'gdrive_document_url','video','post_url'];

    public function editor(){
        return $this->belongsTo(Editor::class);
    }

    public function credential(){
        return $this->belongsTo(Wp_credential::class);
    }

    public function post_infos(){
        return $this->hasOne(Wp_post_info::class);
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
