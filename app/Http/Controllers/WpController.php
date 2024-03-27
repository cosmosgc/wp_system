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

                $image=Wp_post_content::find($request->id);
                $login=Wp_credential::where('wp_domain',$image->domain)->get();
                //dd($image);
                $domain_with_ssl=preg_replace('/^(?!https?:\/\/)/', 'https://',$login[0]->wp_domain);
                $newPost=$this->wpService->postBlogContent($image->keyword,$image->theme,$image->category,$image->post_content,$image->insert_image,$image->post_image,$domain_with_ssl,$login[0]->wp_login,$login[0]->wp_password);
                $update_meta=$this->wpService->updateYoastRankMath($domain_with_ssl,intval($request->id),isset($image->keyword)?$image->keyword:'placeholder');
                return $newPost;
            }

            public function updateYoast(Request $request){
                $image=Wp_post_content::find($request->id);
                $update_meta=$this->wpService->updateYoastRankMath($image->domain,intval($request->post_id),isset($image->keyword)?$image->keyword:'placeholder');
                return response(200);
            }

}
