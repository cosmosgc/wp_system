<?php

namespace App\Services;

use GuzzleHttp\Client;


class Wp_service{

    public function postBlogContent($title,$content,$image,$domain,$login,$password){
        //$title = $title->input('title');
        //$content = $content->input('content');

        $client = new Client();

         // Upload da imagem de destaque (featured image)
         $imageID=null;

         if(!empty($image->file('featured_image'))){
            $featuredImage = $image->file('featured_image');
            $featuredImagePath = storage_path('app/public/featured-images/' . $featuredImage->getClientOriginalName());
            $featuredImage->move(storage_path('app/public/featured-images'), $featuredImage->getClientOriginalName());

            $responseUploadImagem = $client->post($domain.'/wp-json/wp/v2/media', [
                'auth' => [$login, $password],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($featuredImagePath, 'r'),
                        'filename' => $featuredImage->getClientOriginalName(),
                    ],
                ],
            ]);

            $imageID = json_decode($responseUploadImagem->getBody())->id;

         }
         

 

        $response = $client->post($domain.'/wp-json/wp/v2/posts', [
            'auth' => [$login, $password],
            'form_params' => [
                'title' => $title,
                'content' => $content,
                'featured_media' => $imageID,
                // Adicione mais parâmetros conforme necessário
            ],
        ]);

        return $response->getBody();
    }
}