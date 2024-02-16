<?php

namespace App\Services;
use App\Models\Wp_post_content;
use Google\Client;
use Google\Service\Docs;


class PostFileService{


    public function showUploadForm()
    {
        return view('GoogleDocs');
    }

    public function insertCSV($data){
        $new_content=Wp_post_content::create([
            'theme'=>$data['theme'],
            'keyword'=>$data['keyword'],
            'category'=>$data['category'],
            'anchor_1'=>$data['anchor_1'],
            'url_link_2'=>$data['url_link_2'],
            'do_follow_link_1'=>isset($data['do_follow_link_1']) && $data['do_follow_link_1'] === true ? 1 : 0,
            'anchor_2'=>$data['anchor_2'],
            'do_follow_link_2'=>isset($data['do_follow_link_2']) && $data['do_follow_link_2'] === true ? 1 : 0,
            'anchor_3'=>$data['anchor_3'],
            'url_link_3'=>'gfhfdkjghkdjhgkjd',
            'post_image'=>'adsaadda',
            'internal_link'=>$data['internal_link'],
            'post_content'=>$data['post_content'],
            'insert_image'=>isset($data['insert_image']) && $data['insert_image'] === 'Sim' ? 1 : 0

        ]);
    }

    public function importGoogleDocs($id)
    {
        // Ler o token de acesso da sessão
        $accessToken = session('google_access_token');
        // Se o token de acesso não estiver presente na sessão, redirecione para o processo de autenticação do Google
        if (!$accessToken) {
            return redirect()->route('google.redirect');
        }
    
        // Criar cliente Google
        $client = new Client();
        $client->setAccessToken($accessToken);
    
        // Verificar se o token de acesso expirou
        // if ($client->isAccessTokenExpired()) {
        //     // Se o token de acesso expirou, você pode optar por renová-lo ou redirecionar o usuário para autenticação novamente
        //     // Aqui, estou redirecionando o usuário para autenticação novamente
        //     return redirect()->route('google.redirect');
        // }
    
        // // Renovar o token de acesso, se necessário, usando o token de atualização
        // if ($client->getRefreshToken() && $client->isAccessTokenExpired()) {
        //     $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        //     // Atualizar o token de acesso na sessão
        //     session(['google_access_token' => $client->getAccessToken()]);
        // }
    
        // Inicializar serviço de Documentos do Google
        $service = new Docs($client);
    
        try {
            // Fazer a solicitação para obter o conteúdo do documento
            $response = $service->documents->get($id);
            $content=$response->getBody()->getContent();
            $post_content='';

            foreach ($content as $element) {
                if (isset($element->paragraph)) {
                    $paragraph = $element->paragraph;
                    foreach ($paragraph->elements as $paragraphElement) {
                        if (isset($paragraphElement->textRun)) {
                            $textRun = $paragraphElement->textRun;
                             $post_content.=$textRun->content;
                        }
                    }
                }
            }
            return $post_content;
            // Agora você pode usar o $content como o conteúdo do document
            
        } catch (\Exception $e) {
            // Lidar com qualquer erro que ocorra ao tentar obter o conteúdo do documento
            return redirect()->back()->with('error', 'Failed to fetch document content: ' . $e->getMessage());
        }
    }
    
    
    
}