<?php

namespace App\Http\Controllers;

use App\Services\EditorService;
use Illuminate\Http\Request;


class EditorController extends Controller{
    protected $editorService;

    public function __construct(EditorService $editService)
    {
        $this->editorService=$editService;
    }

    public function processEditor(Request $request){

        $new_editor=$this->editorService->insertEditor($request);

        return redirect()->route('dashboard.editorCreated');

    }
}