<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ia_credential extends Model
{
    use HasFactory;

    protected $fillable=['open_ai','Editor_id'];

    public function editor(){
        return $this->belongsTo(Editor::class);
    }
}
