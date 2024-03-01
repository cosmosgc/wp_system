<?php

namespace App\Http\Controllers;

use App\Models\Editor;
use App\Models\Wp_post_content;
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
            $image=Wp_post_content::find($request->id);
            $login=Editor::find($request->user_id);
            foreach($login->links as $credential){
                $newPost=$this->wpService->postBlogContent($image->keyword,$image->theme,$image->post_content,$image->insert_image,$image->post_image,$credential->wp_domain,$credential->wp_login,$credential->wp_password);
                return $newPost;
                
            }
           

            
        }

}
