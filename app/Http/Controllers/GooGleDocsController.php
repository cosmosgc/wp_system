<?php

namespace App\Http\Controllers;

use App\Services\CsvReaderService;
use App\Services\PostContentService;
use App\Services\PostFileService;
use Illuminate\Http\Request;

class GooGleDocsController extends Controller
{
    //

protected $googleDocsService;
protected $postService;

public function __construct(PostFileService $googleDocs)
{
    $this->googleDocsService=$googleDocs;
}

public function insertDocOnDB(Request $request){


    if (!$request->session()->has('google_access_token')) {
        // Se o token de acesso não estiver presente, redirecione para o processo de autenticação do Google
        return redirect()->route('google.redirect');
    }
    if($request->has('google_docs')){
        $doc_content=$this->googleDocsService->importGoogleDocs($request->google_docs);
        $content=array(
            'theme'=>'gjkhfhgjkdzg',
            'keyword'=>'fdjkghdksjghsjkghskj',
            'category'=>'gfjdhkghjkdhgdkjhgdjk',
            'anchor_1'=>'gfjkdfhgkjdhgjkdhgkjdg',
            'url_link_2'=>'fdlkgjfdlgjfdlkgjdlk',
            'do_follow_link_1' => null,
            'anchor_2'=>'gfjkdhgdkjghjkdghkjdh',
            'do_follow_link_2' => null,
            'anchor_3'=>'gfjkldjgkldjglkdjgkl',
            'post_content'=>$doc_content,
            'post_image'=>null,
            'internal_link'=>'fdghjdkjghjkdhgjkdhkj'
        );
        $save_post=$this->googleDocsService->insertCSV($content);

    }
}

public function createDocFromDb(Request $request){

    if (!$request->session()->has('google_access_token')) {
        // Se o token de acesso não estiver presente, redirecione para o processo de autenticação do Google
        return redirect()->route('google.redirect');
    }

    $doc_created=$this->googleDocsService->createAndPopulateGoogleDoc($request->title,$request->content);
    return $doc_created;


}
}
