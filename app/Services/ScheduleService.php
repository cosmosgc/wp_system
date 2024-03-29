<?php
// app/Services/ProcessamentoPostagemService.php

namespace App\Services;

use App\Models\Wp_post_content;
use App\Models\Editor;
use App\Models\Wp_credential;
use App\Services\Wp_service;
use Carbon\Carbon;

class ScheduleService
{


    public function processScheduledPosts()
    {
        // Recupera todas as postagens agendadas com base no campo 'schedule' preenchido
        $scheduledPosts = Wp_post_content::whereNotNull('schedule_date')->get();
        $nullschedule = Wp_post_content::whereNull('schedule_date')->get();
        //dd($nullschedule);
        if(!empty($scheduledPosts)){
            foreach ($scheduledPosts as $posts) {
                // Verifica se a data de agendamento é no futuro
              //dd(Carbon::parse($posts->schedule_date)->isFuture());
                //$editor=Editor::find($posts->Editor_id);
                $links=Wp_credential::all();
                
                    // Recupera o editor da postage
                   // Posta o conteúdo do blog
                    foreach ($links as $credential) {
                        if (!Carbon::parse($posts->schedule_date)->isFuture() && $posts->status="Não postado") {
                            $newPost = new Wp_service();
                            $newPost->postBlogContent(
                                $posts->keyword,
                                $posts->theme,
                                $posts->category,
                                $posts->post_content,
                                $posts->insert_image,
                                $posts->post_image,
                                $posts->domain,
                                $credential->wp_login,
                                $credential->wp_password,
                                
                            );
                    }
                        
                    }
                
            }

        }else{
            return 'no-data';

        }

        if(!empty($nullschedule)){
            foreach ($nullschedule as $posts) {
                // Verifica se a data de agendamento é no futuro
                //dd($posts);
                
                    $editor=Editor::find($posts->Editor_id);
                    //dd($editor->links);
                    $links=Wp_credential::all();
                    foreach ($links as $credential) {
                        //dd($links);
                        if($posts->status=="Não publicado"){
                        $newPost = new Wp_service();
                        $newPost->postBlogContent(
                            $posts->keyword,
                            $posts->theme,
                            $posts->category,
                            $posts->post_content,
                            $posts->insert_image,
                            $posts->post_image,
                            $posts->domain,
                            $credential->wp_login,
                            $credential->wp_password,
                            
                        );
                    }
                        
                        
                    }

                

            }
            

        }else{
            return 'no-data';
        }



    
    }
}
