<?php

namespace App\Http\Controllers;

use App\Services\CsvReaderService;
use App\Services\PostContentService;
use App\Services\PostFileService;
use Illuminate\Http\Request;
use DateTime;

class CsvReaderController extends Controller
{
    protected $reader;
    protected $postConfigService;

    public function __construct(CsvReaderService $readerService,PostFileService $postConfig)
    {
        $this->reader=$readerService;
        $this->postConfigService=$postConfig;
    }

    public function ImportCsv(Request $request){
        if($request->hasFile('csv_file')){
            $data_csv=$this->reader->CsvToJson($request);
            foreach ($data_csv as $key => $row) {
                foreach ($row as $subKey => $value) {
                    $data_csv[$key][$subKey] = is_string($value) ? utf8_encode($value) : $value;
                }
            }
            $data=$data_csv;
            foreach($data as $dt){
                // dd($dt);
                $dataAtual = new DateTime();
                $dataAtual->modify('+' . intval($dt['Programacao de Postagem']) . ' days');
                $dataAtual->format('Y-m-d H:i:s');
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
                    'schedule_date'=>$dataAtual,
                    'user_id'=>$request->user_id,

                );


                $new_csv_content=$this->postConfigService->insertCSV($content);

            }

            //dd($processed_data);
        }

        return redirect()->route('configCreated');
    }
}
