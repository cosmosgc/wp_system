<?php

namespace App\Http\Controllers;

use App\Models\Wp_post_content;
use Illuminate\Http\Request;

class searchController extends Controller
{
    //
    public function searchByQuery(Request $request){
        $results=Wp_post_content::where ('domain','like','%'.$request->input('domain').'%')->get();
         return view('dashboard.searchResult',['search'=>$results]);
    }
}
