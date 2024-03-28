<?php
// app/Services/ProcessamentoPostagemService.php

namespace App\Services;

use App\Models\Wp_post_content;
use App\Models\Editor;
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
                $editor=Editor::find($posts->Editor_id);
                if (!Carbon::parse($posts->schedule_date)->isFuture() && $posts->status="Não postado") {
                    // Recupera o editor da postage
                   // Posta o conteúdo do blog
                    foreach ($editor->links as $credential) {
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
                if($posts->status=="Não postado"){
                    $editor=Editor::find($posts->Editor_id);
                    foreach ($editor->links as $credential) {
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
