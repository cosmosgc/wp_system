<!-- resources/views/upload.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="/submit_file" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="csv_file">Selecione o arquivo CSV:</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="csv_file" name="csv_file">
                        <label class="custom-file-label" for="csv_file">Escolher arquivo</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Importar</button>
            </form>
        </div>
    </div>
</div>

@endsection

