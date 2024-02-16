<?php

namespace App\Http\Controllers;

use App\Services\CsvReaderService;
use App\Services\PostContentService;
use App\Services\PostFileService;
use Illuminate\Http\Request;

class CsvReaderController extends Controller
{
    protected $reader;
    protected $postConfigService;

    public function __construct(CsvReaderService $readerService,PostFileService $postConfig)
    {
        $this->reader=$readerService;
        $this->postConfigService=$postConfig;
    }

    public function showUploadForm()
    {
        return view('upload');
    }

    public function ImportCsv(Request $request){
        if($request->hasFile('csv_file')){
            $data_csv=$this->reader->CsvToJson($request);
            foreach ($data_csv[0] as $key => $value) {
                $data_csv[0][$key] = utf8_encode($value);
            }
            $data=$data_csv[0];
            $content=array(
                'theme'=>$data['Tema'],
                'keyword'=>$data['Keyword'],
                'category'=>$data['Categoria'],
                'anchor_1'=>$data['Ancora 1'],
                'url_link_2'=>$data['URL do Link 2'],
                'do_follow_link_1' => isset($data['Dofollow_link_1']) && $data['Dofollow_link_1'] === 'Sim' ? true : null,
                'anchor_2'=>$data['Ancora 2'],
                'do_follow_link_2' => isset($data['Dofollow_link_2']) && $data['Dofollow_link_2'] === 'Sim' ? true : null,
                'anchor_3'=>$data['Ancora 3'],
                'internal_link'=>$data['Link Interno']
            );

            

            $new_csv_content=$this->postConfigService->insertCSV($content);
        }
    }
}
