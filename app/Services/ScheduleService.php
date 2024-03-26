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
        

        if(!empty($scheduledPosts)){
            foreach ($scheduledPosts as $posts) {
                // Verifica se a data de agendamento é no futuro
               //dd(Carbon::parse($posts->schedule_date)->isFuture());
              
                if (!Carbon::parse($posts->schedule_date)->isFuture()) {
                    // Recupera o editor da postage
                   
                    $editor=Editor::find($posts->Editor_id);
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
                            $credential->wp_domain,
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
