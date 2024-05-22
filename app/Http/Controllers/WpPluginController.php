<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wp_credential;

class WpPluginController extends Controller
{
    //
    public function ping($id)
    {
        try {
            $site_cred = $this->get_site_cred_by_id($id);
            //ping the wp plugin route
            $response = $site_cred;
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function get_site_cred_by_id($id){
        try {
            $site_cred = Wp_credential::find($id);
            return $site_cred;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'message' => "Não foi encontrado o site no banco de dados"], 500);
        }
    }
}
