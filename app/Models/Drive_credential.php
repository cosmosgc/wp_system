<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drive_credential extends Model
{

    use HasFactory;

    protected $fillable=['client_id','project_id','auth_uri','token_uri','auth_provider_x509_cert_url','client_secret','redirect_uris','api_key','Editor_id'];

    public function editor(){
        return $this->belongsTo(Editor::class);
    }
}
