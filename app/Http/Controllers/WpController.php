<?php

namespace App\Http\Controllers;

use App\Services\Wp_service;
use Illuminate\Http\Request;

class WpController extends Controller
{
    //
    protected $wpService;

    public function __construct(Wp_service $service)
    {
        $this->wpService=$service;
    }

    public function createBlogPost(Request $request){
        $newPost=$this->wpService->postBlogContent($request->title,$request->content,$request->image,$request->domain,$request->login,$request->password);
    }

}
