<?php

namespace App\Http\Controllers;

use App\Services\EditorService;
use Illuminate\Http\Request;
use App\Models\Editor;


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
    public function destroy($id)
{
    $editor = Editor::find($id);
    if ($editor) {
        $editor->delete();
        return redirect()->route('dashboard.show')->with('success', 'Editor excluído com sucesso!');
    } else {
        return redirect()->route('dashboard.show')->with('error', 'Editor não encontrado!');
    }
}

    public function updateEditor(Request $request){
        $this->editorService->updateEditor($request);
    }

}