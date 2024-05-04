<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wp_post_info extends Model
{
    use HasFactory;

    protected $fillable=['post_name','post_id','post_url','Config_id'];

    public function get_config(){
        return  $this->belongsTo(Wp_post_content::class);
    }
}
