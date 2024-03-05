<?php

namespace App\Http\Controllers;

use App\Models\Editor;
use App\Models\Wp_credential;
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
           
            if(!empty($request->domain)){
                $image=Wp_post_content::find($request->id);
                $login=Wp_credential::where('wp_domain',$request->domain)->get();
                $newPost=$this->wpService->postBlogContent($image->keyword,$image->theme,$image->post_content,$image->insert_image,$image->post_image,$login[0]->wp_domain,$login[0]->wp_login,$login[0]->wp_password);
                return $newPost;
            }else{
                return response('sem dominio',400);
            }
            
           
            
           
                
                
        
           

            
        }

}
