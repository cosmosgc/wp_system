<?php

namespace App\Services;

use App\Models\Wp_post_content;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Google_Client;
use Google_Service_Drive;

class PostContentService{

    public function insertPostContent($data,$doContent){

        // Verifica se uma imagem foi enviada
        if ($data->hasFile('post_image') && $data->file('post_image')->isValid()) {
            // Obtém o arquivo da imagem
            $image = $data->file('post_image');
            
            // Salva a imagem no armazenamento (storage) do Laravel
            $imagePath = $image->store('images', 'public');
        } elseif ($data->filled('gdrive_url')) {
            // Se uma URL do Google Drive foi fornecida, faça o download da imagem do Google Drive
            $imagePath = $this->downloadImageFromGoogleDrive($data->gdrive_url,$data);
        } elseif ($data->filled('image_url')) {
            // Se uma URL de imagem padrão foi fornecida, faça o download da imagem
            $imagePath = $this->downloadImageFromUrl($data->image_url);
        } else {
            // Se nenhum arquivo ou URL de imagem for fornecido, defina o caminho da imagem como null
            $imagePath = null;
        }

        
        $new_content=Wp_post_content::create([
            'theme'=>$data->theme,
            'keyword'=>$data->keyword,
            'category'=>$data->category,
            'anchor_1'=>$data->anchor_1,
            'url_link_2'=>$data->url_link_2,
            'do_follow_link_1'=>$data->has('do_follow_link_1')?1:0,
            'anchor_2'=>$data->anchor_2,
            'do_follow_link_2'=>$data->has('do_follow_link_2')?1:0,
            'anchor_3'=>$data->anchor_3,
            'post_image'=>$imagePath,
            'internal_link'=>$data->internal_link,
            'post_content'=>isset($doContent)?$doContent:null,
            'insert_image'=>$data->has('insert_image')?1:0

        ]);

        
    }


    private function downloadImageFromUrl($imageUrl)
    {
        // Gera um nome único para o arquivo usando o helper Str
        $fileName = Str::random(20) . '.png';
        
        // Faz o download da imagem do URL fornecido
        $imageContents = file_get_contents($imageUrl);

        // Salva a imagem no armazenamento (storage) do Laravel
        $storagePath = 'images/' . $fileName;
        Storage::disk('public')->put($storagePath, $imageContents);

        return $storagePath;
    }


    private function downloadImageFromGoogleDrive($imageUrl,$data)
    {
        // Cria uma instância do cliente Google Client
        $client = new Google_Client();
        $client->setApplicationName('Google Drive API');
        $client->setScopes(Google_Service_Drive::DRIVE_READONLY); // Escopo de somente leitura para acessar os arquivos
        $client->setAuthConfig(base_path('credentials.json')); // Caminho para o arquivo de credenciais da API

        // Cria uma instância do serviço Google Drive
        $service = new Google_Service_Drive($client);

        // ID da pasta no Google Drive que contém as imagens
        $folderId = $data->folder_id;

        // Lista os arquivos na pasta especificada
        $results = $service->files->listFiles([
            'q' => "'$folderId' in parents",
            'fields' => 'files(id, name)',
        ]);

        // Escolhe um arquivo aleatório da lista
        $randomFile = collect($results->getFiles())->random();

        // Faz o download do arquivo selecionado
        $fileId = $randomFile->getId();
        $response = $service->files->get($fileId, ['alt' => 'media']);

        // Salva o conteúdo do arquivo no armazenamento do Laravel
        $fileName = Str::random(20) . '_' . $randomFile->getName();
        Storage::disk('public')->put('images/' . $fileName, $response->getBody()->getContents());

        // Retorna o caminho da imagem baixada
        return 'images/' . $fileName;
    }
}
