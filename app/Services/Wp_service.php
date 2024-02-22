<?php

namespace App\Services;

use GuzzleHttp\Client;


class Wp_service{

    public function postBlogContent($keyword,$title,$content,$image,$domain,$login,$password){
        //$title = $title->input('title');
        //$content = $content->input('content');

        $client = new Client();

        $featuredImagePath = storage_path('app/public/'.$image);

        // Verifique se a imagem existe no caminho especificado
        if(file_exists($featuredImagePath)) {
            // Obtenha o nome do arquivo da imagem
            $featuredImageName = basename($featuredImagePath);

            $additionalData = [
                'alt_text' => $keyword,
                'title' => $keyword,
                'caption' => $keyword,
                'description' => $keyword,
            ];

            // Faça a requisição para enviar a imagem para o WordPress
            $responseUploadImagem = $client->post($domain.'/wp-json/wp/v2/media', [
                'auth' => [$login, $password],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($featuredImagePath, 'r'),
                        'filename' => $featuredImageName,
                    ],

                    [
                        'name' => 'alt_text',
                        'contents' => $additionalData['alt_text'],
                    ],
                    [
                        'name' => 'title',
                        'contents' => $additionalData['title'],
                    ],
                    [
                        'name' => 'caption',
                        'contents' => $additionalData['caption'],
                    ],
                    [
                        'name' => 'description',
                        'contents' => $additionalData['description'],
                    ],
                ],
            ]);

            // Extraia o ID da imagem enviada
            $imageID = json_decode($responseUploadImagem->getBody())->id;
        } else {
            // Caso a imagem não seja encontrada, você pode tomar ação apropriada aqui
            $imageID = null;
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