<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function register(){
        return view('dashboard.register');
    }

    public function ediorCreated(){
        return view('dashboard.editorCreated');
    }

    public function configCreated(){
        return view('dashboard.configCreated');
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

    public function listPostConfig(){
        return view('dashboard.SubmitPosts');
    }  
    
    public function docCreated(){
        return view('dashboard.DocumentCreated');
    }

    public function docImported(){
        return view('dashboard.DocumentImported');
    }

    public function tokenInserted(){
        return view('dashboard.tokeninserted');
    }

    public function importCsv(){
        return view('dashboard.upload');
    }

    public function insertGptToken(){
        return view('dashboard.configia');
    }

}
