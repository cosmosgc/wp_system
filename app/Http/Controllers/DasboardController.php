<?php

namespace App\Http\Controllers;

use App\Models\Editor;
use App\Models\Ia_credential;
use Illuminate\Http\Request;
use App\Models\Wp_post_content;

class DasboardController extends Controller
{
    //

    public function index()
    {
        return view('dashboard.index');
    }

    public function login(){
        return view('login');
    }

    public function show(Request $request)
    {
        $page = $request->input('page', 'home');
        return view('dashboard.show', compact('page'));
    }

    public function profile()
    {
        return view('dashboard.profile');
    }

    public function ListEditor(){
        $editors = Editor::all();
        return view('dashboard.editorList',['editors'=>$editors]);
    }

    public function register(){
        return view('dashboard.register');
    }

    public function ediorCreated(){
        return view('dashboard.editorCreated');
    }

    public function configCreated(){
        return view('dashboard.configCreated');
    }

    public function configUpdated(){
        return view('dashboard.configUpdate');
    }

    public function contentCreation(){
        return view('dashboard.contentConfig');
    }

    public function postCreation(){
        return view('dashboard.createPost');
    }

    public function DocsUpload(){
        return view('GoogleDocs');
    }

    public function DocCreation(){
        return view('dashboard.GoogleDocCreation');
    }

    public function listPostConfig(Request $request){
        $results=null;
        if(!empty($request->input('query'))){
            $results=Wp_post_content::where ('theme','like','%'.$request->input('query').'%')->orWhere('domain','like','%'.$request->input('query').'%')->get();
        }
        
        return view('dashboard.SubmitPosts',['search'=>$results]);
    }

    public function docCreated(){
        return view('dashboard.DocumentCreated');
    }

    public function docImported(){
        return view('dashboard.DocumentImported');
    }

    public function tokenInserted(){
        return view('dashboard.tokenInserted');
    }

    public function importCsv(){
        return view('dashboard.upload');
    }
    public function gDrivePort(){
        return view('dashboard.gdriveport');
    }

    public function gptTeste(){
        
        $valorCodificado = request()->cookie('editor');
        $user=explode('+',base64_decode($valorCodificado));
        $editor=Editor::where('name',$user)->get();
        $contents=Editor::find($editor[0]->id);

        return view('demandTest',['contents'=>$contents->postContents]);
    }


    public function insertGptToken(){
        if(empty(Ia_credential::all())){
            $token=null;
        }else{
            $token=Ia_credential::all();
        }

        $valorCodificado = request()->cookie('editor');
        $user=explode('+',base64_decode($valorCodificado));

        return view('dashboard.configIa',['ia_token'=>$token,'editor'=>$user[0]]);
    }

    public function insertWpCredential(){
        return view('dashboard.wordpressCredential');
    }

    public function listWpCredential(){
        $user_credentials=Editor::all();
        $editor_credentials=[];
        foreach($user_credentials as $credentials){
            if(!empty($credentials->links)){
                foreach ($credentials->links as $link) {
                    $editor_credentials[] = $link;
                }
            }

        }
        return view('dashboard.wpCredentialList',['credentiais'=>$editor_credentials,'editor'=>$user_credentials]);

    }


    public function listIaCredentials(){
        $user_credentials=Editor::all();
        $editor_ia_credentials=[];
        foreach($user_credentials as $credentials){
            if(!empty($credentials->iaCredentials)){
                foreach ($credentials->iaCredentials as $iaCredential) {
                    $editor_ia_credentials[] = $iaCredential;
                }
            }

        }

        return view('dashboard.IatokenList',['Iacredentials'=>$editor_ia_credentials,'editor'=>$user_credentials]);

    }

    public function siteCredentialCreated(){
        return view('dashboard.SiteCredentialCreated');
    }

    public function tokenDeleted(){
        return view('dashboard.deletedToken');
    }

}
