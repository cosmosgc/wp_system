<?php

namespace App\Services;

use App\Models\Editor;
use App\Models\Wp_post_content;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Google_Client;
use Google_Service_Drive;
use Monolog\Handler\DeduplicationHandler;

class PostContentService{

    public function insertPostContent($data,$doContent){

        $image= $this->processImage($data);

        $user_id=Editor::where('name',$data->session_user)->get();

        $new_content=Wp_post_content::create([
            'theme'=>$data->theme,
            'keyword'=>$data->keyword,
            'category'=>$data->category,
            'anchor_1'=>$data->anchor_1,
            'url_link_1'=>$data->url_link_1,
            'do_follow_link_1'=>$data->has('do_follow_link_1')?1:0,
            'anchor_2'=>$data->anchor_2,
            'url_link_2'=>$data->url_link_2,
            'do_follow_link_2'=>$data->has('do_follow_link_2')?1:0,
            'anchor_3'=>$data->anchor_3,
            'url_link_3'=>$data->url_link_3,
            'do_follow_link_3'=>$data->has('do_follow_link_3')?1:0,
            'post_image'=>$image,
            'internal_link'=>$data->internal_link,
            'post_content'=>isset($doContent)?$doContent:null,
            'insert_image'=>$data->has('insert_image')?1:0,
            'status'=>'Não publicado',
            'schedule_date'=>$data->schedule,
            'domain'=>$data->domain,
            'gdrive_url'=>$data->gdrive_url,
            'gdrive_document_url'=>$data->gdrive_document_url,
            'video'=>$data->video,
            'internal_link'=>$data->internal_link,
            'Project_id'=>$data->project_id,
            'Editor_id'=>$user_id[0]->id,
        ]);



    }


    public function updateConfig($data){
        $updated_content=Wp_post_content::find(($data->id));

        $updated_image =$this->processImage($data);

        $updated_content->theme = isset($data->theme) ? $data->theme : '';
        $updated_content->keyword = isset($data->keyword) ? $data->keyword : '';
        $updated_content->category = isset($data->category) ? $data->category : '';
        $updated_content->anchor_1 = isset($data->anchor_1) ? $data->anchor_1 : '';
        $updated_content->url_link_1 = isset($data->url_link_1) ? $data->url_link_1 : '';
        $updated_content->do_follow_link_1 = isset($data->do_follow_link_1) ? $data->do_follow_link_1 : '';
        $updated_content->anchor_2 = isset($data->anchor_2) ? $data->anchor_2 : '';
        $updated_content->url_link_2 = isset($data->url_link_2) ? $data->url_link_2 : '';
        $updated_content->do_follow_link_2 = isset($data->do_follow_link_2) ? $data->do_follow_link_2 : '';
        $updated_content->anchor_3 = isset($data->anchor_3) ? $data->anchor_3 : '';
        $updated_content->url_link_3 = isset($data->url_link_3) ? $data->url_link_3 : '';
        $updated_content->do_follow_link_3 = isset($data->do_follow_link_3) ? $data->do_follow_link_3 : '';
        $updated_content->post_image = $updated_image;
        $updated_content->internal_link = isset($data->internal_link) ? $data->internal_link : '';
        //$updated_content->post_content = isset($data->post_content) ? $data->post_content : '';
        $updated_content->insert_image = isset($data->insert_image) ? $data->insert_image : '';
        $updated_content->status = isset($data->status) ? $data->status : 'Não publicado';
        $updated_content->schedule_date = isset($data->schedule_date) ? $data->schedule_date : null;
        $updated_content->domain = isset($data->domain) ? $data->domain : '';
        $updated_content->gdrive_document_url = isset($data->gdrive_document_url) ? $data->gdrive_document_url : '';
        $updated_content->video = isset($data->video) ?(bool)$data->video : false;
        $updated_content->post_url=isset($data->post_url)?$data->post_url:null;


        $updated_content->save();


    }


    private function processImage($data){
                    // Verifica se uma imagem foi enviada
                    if (isset($data->sys_image)) {
                        // Obtain the temporary file path
                        $tempFilePath = $data->sys_image->path();

                        $originalFileName = $data->sys_image->getClientOriginalName();
                        $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

                        // Remove the extension from the original filename
                        $originalFileNameWithoutExtension = pathinfo($originalFileName, PATHINFO_FILENAME);

                        // Generate a unique filename with the original extension
                        $imageName = $originalFileNameWithoutExtension . '_' . uniqid() . '.' . $extension;


                        // Move the uploaded file to the desired location
                        move_uploaded_file($tempFilePath, storage_path('app/public/images/') . $imageName);

                        // Define the path of the image
                        $imagePath = 'images/' . $imageName;

                    } elseif ($data->filled('gdrive_url') && $data->gdrive_url != '' && $data->gdrive_url != "null") {

                        // Se uma URL do Google Drive foi fornecida, faça o download da imagem do Google Drive
                        $imagePath = $this->downloadImageFromGoogleDrive($data->gdrive_url,$data);
                    } elseif ($data->filled('image_url') && $data->image_url != '') {
                        // Se uma URL de imagem padrão foi fornecida, faça o download da imagem
                        $imagePath = $this->downloadImageFromUrl($data->image_url);
                    } else {
                        // Se nenhum arquivo ou URL de imagem for fornecido, defina o caminho da imagem como null
                        $imagePath = null;
                    }

                return $imagePath;
    }


    public function downloadImageFromUrl($imageUrl)
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


    public function downloadImageFromGoogleDrive($imageUrl, $data)
    {
        //  if ($maxRetries > 5) {
        //    return response()->json('maximo de tentativas excedida',500);
        //  }

        try {
            // Cria uma instância do cliente Google Client
            $client = new Google_Client();
            $credentials = Editor::where('name', $data->session_user)->get();
            $client->setApplicationName('Google Drive API');
            // if (!isset($credentials[0]->GoogleCredentials->api_key)) {
            //     return;
            // }
            $client->setDeveloperKey($credentials[0]->GoogleCredentials->api_key); // Usando a chave de API

            // Cria uma instância do serviço Google Drive
            $service = new Google_Service_Drive($client);

            // ID da pasta no Google Drive que contém as imagens
            $folderId = $data->gdrive_url;

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
            dd($fileName);
            Storage::disk('public')->put('images/' . $fileName, $response->getBody()->getContents());

            // Se o download for bem-sucedido, retornamos o caminho da imagem
            return 'images/' . $fileName;
        } catch (\Exception $e) {
            // Em caso de erro, chamamos a função recursivamente
           return $e;
        }
    }

}
