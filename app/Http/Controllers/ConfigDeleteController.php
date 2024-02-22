<?php

namespace App\Http\Controllers;

use App\Models\Wp_post_content;
use Illuminate\Http\Request;

class ConfigDeleteController extends Controller
{
    //
    public function deleteConfig(Request $request){
        $delete_request =Wp_post_content::find(intval($request->id));
        $deletion=$delete_request->delete();
        return $deletion;
    }
}
