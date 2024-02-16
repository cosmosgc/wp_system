<?php

namespace App\Http\Controllers;

use App\Services\CsvReaderService;
use App\Services\PostContentService;
use App\Services\PostFileService;
use Illuminate\Http\Request;

class PostContentController extends Controller
{
    //

    protected $postConfigService;
    protected $GoogleDoc;

    public function __construct(PostContentService $postConfig,PostFileService $uploadDoc)
    {
        $this->postConfigService=$postConfig;
        $this->GoogleDoc=$uploadDoc;
    }

    public function saveContent(Request $request){
        if(!empty($request->doc_id)){
            $docsContent=$this->GoogleDoc->importGoogleDocs($request->doc_id);
        }else{
            $docsContent=null;
        }
       

        $new_post_content=$this->postConfigService->insertPostContent($request,$docsContent);
    }
}
