<?php
// app/Services/ProcessamentoPostagemService.php

namespace App\Services;

use App\Models\Wp_post_content;
use App\Models\Editor;
use App\Services\WpService;
use Carbon\Carbon;

class ScheduleService
{
    protected $wpService;

    public function __construct(Wp_service $wpService)
    {
        $this->wpService = $wpService;
    }

    public function processScheduledPosts()
    {
        // Recupera todas as postagens agendadas com base no campo 'schedule' preenchido
        $scheduledPosts = Wp_post_content::whereNotNull('schedule')->get();

        foreach ($scheduledPosts as $posts) {
            // Verifica se a data de agendamento é no futuro
            if (Carbon::parse($posts->schedule)->isFuture()) {
                // Recupera o editor da postagem
                $editor = $posts->editor;

                // Posta o conteúdo do blog
                foreach ($editor->links as $credential) {
                    $newPost = $this->wpService->postBlogContent(
                        $posts->keyword,
                        $posts->theme,
                        $posts->post_content,
                        $posts->post_image,
                        $credential->wp_domain,
                        $credential->wp_login,
                        $credential->wp_password
                    );
                }
            }
        }

    
    }
}
