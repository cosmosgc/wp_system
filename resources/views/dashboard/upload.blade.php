<!-- resources/views/upload.blade.php -->
@extends('layouts.app')

@php
    use App\Models\Editor;

    $valorCodificado = request()->cookie('editor');
    $user=explode('+',base64_decode($valorCodificado));
    $post_configs= Editor::where('name',$user[0])->get();
@endphp

@section('content')
<div class="container">
    <h3 class="mt-5 mb-4 text-center">Faça o upload de CSV de configurações prontas</h3>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="/submit_file" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="csv_file" class="download-label d-flex align-items-center justify-content-center border rounded p-3">
                        <svg class="download-icon mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        <span>upload do arquivo</span>
                    </label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="csv_file" name="csv_file">
                    </div>
                </div>
                <input type="hidden" name="user_id" value="{{$post_configs[0]->id}}">
                <button type="submit" class="btn btn-primary btn-block">Importar</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>

<script>
    const fileInput = document.getElementById('csv_file');

    const fileNameContainer = document.querySelector('.download-label');

fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        // Aqui você pode exibir o nome do arquivo em algum lugar no documento
        const file_name= document.createElement('div');
        file_name.innerHTML=file.name;
        fileNameContainer.insertAdjacentElement("beforebegin", file_name);
    }
});
</script>
</script>

@endsection

