<?php

namespace App\Http\Controllers;

use App\Models\Wp_post_content;
use Illuminate\Http\Request;

class searchController extends Controller
{
    //
    public function searchByQuery(Request $request){
        $results=Wp_post_content::where ('theme','like','%'.$request->input('query').'%')->get();
         return view('dashboard.searchResult',['search'=>$results]);
    }
}
