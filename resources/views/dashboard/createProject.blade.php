@extends('layouts.app')

@section('content')
<form action="{{route('createProject')}}" method="post" class="mt-4">
    @csrf
    <div class="form-group">
        <label for="index">Index:</label>
        <input type="number" class="form-control" name="index" id="index" min="0" max="10">
    </div>
    <div class="form-group">
        <label for="project_name">Nome do Projeto:</label>
        <input type="text" class="form-control" name="project_name" id="project_name">
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>

@endsection