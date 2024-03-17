<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ia_credential extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable=['open_ai','Editor_id','language','writing_style','writing_tone','sections','pagraphs'];

    public function editor(){
        return $this->belongsTo(Editor::class);
    }
}
