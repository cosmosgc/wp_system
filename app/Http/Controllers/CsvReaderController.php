<?php

namespace App\Http\Controllers;

use App\Services\CsvReaderService;
use App\Services\PostContentService;
use App\Services\PostFileService;
use Illuminate\Http\Request;
use DateTime;
use PhpParser\Node\Stmt\TryCatch;

class CsvReaderController extends Controller
{
    protected $reader;
    protected $postConfigService;
    protected $imageService;

    public function __construct(CsvReaderService $readerService,PostFileService $postConfig,PostContentService $imageService)
    {
        $this->reader=$readerService;
        $this->postConfigService=$postConfig;
        $this->imageService=$imageService;
    }



    public function ImportCsv(Request $request){
        // dd($request->csvData);
        // dd($request->user_id);
        $content=null;
        $valorCodificado = request()->cookie('editor');
        $user=explode('+',base64_decode($valorCodificado));
        if($request->hasFile('csv_file') || isset($request->csvData)){
            if($request->hasFile('csv_file')) {
                $data_csv=$this->reader->CsvToJson($request);
            }

            if (isset($request->csvData) && is_array($request->csvData)) {
                // Merge additional data with the existing CSV data
                $data_csv = [];
                $data_csv[] = $request->csvData;
            }


            foreach ($data_csv as $key => $row) {
                foreach ($row as $subKey => $value) {
                    $data_csv[$key][$subKey] = is_string($value) ? mb_convert_encoding($value, 'UTF-8', 'auto')  : $value;
                }
            }
            // dd($data_csv);
            $data=$data_csv;
            if (!isset($request->csvData)){
                $newData=array_pop($data);
            }else{
                $newData=($data);
            }

            if($request->docType=='config_creation'){
                foreach($data as $dt){
                    $dataAtual = new DateTime();
                    if(intval($dt['Programacao de Postagem'])<0){
                       $dataAtual->modify('-' . intval($dt['Programacao de Postagem']) . ' days');
                    }else{
                        $dataAtual->modify('+' . intval($dt['Programacao de Postagem']) . ' days');
                    }

                    $addImage=null;
                    $folders_part=null;
                    $dataAtual->format('Y-m-d H:i:s');
                    $video = trim($dt['Video']," \t\n\r\0\x0B");

                     $url = $dt['Imagem'];
                     $path = parse_url($url, PHP_URL_PATH);
                     if(!empty($url)){
                        $folders_part = explode('/folders/', $path)[1];
                     }

                     $dataUser=array('session_user'=>$user[0],'gdrive_url'=>$folders_part);
                     $teste=json_encode($dataUser);
                     $userData=json_decode($teste);
                     if(!empty($dt['Imagem'])){
                        try {
                            $addImage=$this->imageService->downloadImageFromGoogleDrive('',$userData);
                            if (strpos($addImage, '<html>') === 0) {
                                // Handle the case where $addImage starts with <html>
                                // For example, log an error, throw an exception, or set $addImage to null
                                error_log("A imagem de downloadImageFromGoogleDrive veio como html.");
                                $addImage = null; // Or handle it in another appropriate way
                            }
                        } catch (\Throwable $th) {
                            return $th;
                        }

                     }

                    $content=array(
                        'theme'=>$dt['Tema'],
                        'keyword'=>$dt['Keyword'],
                        'category'=>$dt['Categoria'],
                        'anchor_1'=>$dt['Ancora 1'],
                        'url_link_1'=>$dt['URL do Link 1'],
                        'url_link_3'=>$dt['URL do Link 3'],
                        'do_follow_link_1' => isset($dt['Dofollow_link_1']) && $dt['Dofollow_link_1'] === 'Sim' ? true : null,
                        'anchor_2'=>$dt['Ancora 2'],
                        'url_link_2'=>$dt['URL do Link 2'],
                        'do_follow_link_2' => isset($dt['Dofollow_link_2']) && $dt['Dofollow_link_2'] === 'Sim' ? true : null,
                        'anchor_3'=>$dt['Ancora 3'],
                        'do_follow_link_3' => isset($dt['Dofollow_link_3']) && $dt['Dofollow_link_3'] === 'Sim' ? true : null,
                        'internal_link'=>isset($dt['Link Interno']) && $dt['Link Interno']==='Sim'?true:null,
                        'domain'=>$dt['Dominio'],
                        'gdrive_document_url'=>$dt['Gdrive'],
                        'video'=>isset($video)&& $video=== 'Sim'? true:null,
                        'schedule_date'=>$dataAtual,
                        'insert_image'=>isset($dt['Insere Imagem no Post']) && $dt['Insere Imagem no Post']==='Sim'?true:null,
                        'post_image'=>$addImage,
                        'Project_id'=>$request->project_id,
                        'user_id'=>$request->user_id,

                    );
                    $new_csv_content=$this->postConfigService->insertCSV($content);

            }
            $c=[];



            }elseif($request->docType=='site_register'){
                foreach($data as $dt){
                    $content=array(
                        'wp_login'=>$dt['login'],
                        'wp_password'=>$dt['password'],
                        'wp_domain'=>$dt['domain'],
                        'user_id'=>$request->user_id,
                    );
                    // dd($content);
                    $new_site_registred=$this->postConfigService->insertSiteCsv($content);
                }
            }

            //dd($processed_data)
        }

        return 200; //redirect()->route('configCreated');
    }
}
