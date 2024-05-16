@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-4">Criar Projetos</h2>
    <form action="{{ route('createProject') }}" method="post" class="mt-4">
        @csrf
        <div class="form-group">
            <label for="project_name">Nome do Projeto:</label>
            <input type="text" class="form-control" name="project_name" id="project_name">
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>
@endsection
