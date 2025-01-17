<?php

namespace App\Services;

use GuzzleHttp\Client;


class Wp_service{

    private $client;

    public function __construct()
    {
        $this->client=new Client();
    }

    public function postBlogContent($keyword,$title,$category,$content,$featured,$image,$domain,$login,$password){
        //$title = $title->input('title');
        //$content = $content->input('content');
        

        $featuredImagePath = storage_path('app/public/'.$image);

        // Verifique se a imagem existe no caminho especificado
        if(!empty($image) && $featured==1) {
            // Obtenha o nome do arquivo da imagem
            $featuredImageName = basename($featuredImagePath);

            $additionalData = [
                'alt_text' => $keyword,
                'title' => $keyword,
                'caption' => $keyword,
                'description' => $keyword,
            ];

            // Faça a requisição para enviar a imagem para o WordPress
            $responseUploadImagem = $this->client->post($domain.'/wp-json/wp/v2/media', [
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
         

 
            $category_slug = $category; // Substitua 'nome_da_categoria' pelo slug (nome) da categoria desejada

            $category_response = $this->client->get($domain.'/wp-json/wp/v2/categories?name=' . $category_slug);
            $category_data = json_decode($category_response->getBody(), true);
            if(!empty($category_data)){
                $response = $this->client->post($domain.'/wp-json/wp/v2/posts', [
                    'auth' => ['sistema', '$k9&VJCCL%9ysPHcEaj97#WL'],
                    'form_params' => [
                        'title' => $title,
                        'content' => $content,
                        'featured_media' => $imageID,
                        'status' => 'publish',
                        'categories'=>[$category_data[0]['id']]
                        
                        // Adicione mais parâmetros conforme necessário
                    ],
                ]);

                return $response->getBody();

            }else{
                return 'nome da categoria não encontrado';
            }
            



        
    }

    public function updateYoastRankMath($domain,$post_id,$keyword){
        //dd($domain,$keyword,$post_id);
        $data = array(
            'post_id' => $post_id,
            'keyword' => $keyword
        );
        
        $ch = curl_init($domain.'/wp-json/wp_manage/v1/update_yoast_keyword/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
        // Verifique a resposta
        if ($response === false) {
            echo 'Erro ao fazer a requisição cURL: ' . curl_error($ch);
        } else {
            echo 'Resposta da requisição: ' . $response;
        }
    }
}