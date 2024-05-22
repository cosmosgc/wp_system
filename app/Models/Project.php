<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Project extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable=['project_name'];

    public function post_config(){
        return $this->hasMany(Wp_post_content::class,'Project_id');
    }

}
