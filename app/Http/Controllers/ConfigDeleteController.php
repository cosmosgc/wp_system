<?php

namespace App\Http\Controllers;

use App\Models\Wp_post_content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigDeleteController extends Controller
{
    //
    public function deleteConfig(Request $request){
        $delete_request =Wp_post_content::find($request->id);
        if (Storage::disk('public')->exists($delete_request->post_image)) {
            // Deleta o arquivo
            Storage::disk('public')->delete($delete_request->post_image);
             // Retorna verdadeiro se a exclusão for bem-sucedida
        }
        $deletion=$delete_request->delete();
        return response()->json($deletion, 200);
    }
}
