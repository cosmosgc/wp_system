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
                    $response=json_decode($newPost);
                    $update_meta=$this->wpService->updateYoastRankMath($request->domain,intval($response->id),isset($image->keyword)?$image->keyword:'placeholder');
                    return $update_meta; 
                }else{
                    return response('sem dominio',400);
                }
                
            }

            public function updateYoast(Request $request){
                $update_meta=$this->wpService->updateYoastRankMath($request->domain,intval($request->id),isset($request->keyword)?$request->keyword:'placeholder');

                return 'aqui'.$update_meta;
            }

}
