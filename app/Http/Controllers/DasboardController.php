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

    public function contentCreation(){
        return view('dashboard.contentConfig');
    }

    public function postCreation(){
        return view('dashboard.createPost');
    }

    public function DocsUpload(){
        return view('GoogleDocs');
    }

}
