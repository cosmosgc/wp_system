@extends('layouts.app')

@section('content')
    <div class="dashboard-content">
        <h3>Create documente</h3>
        <form action="/create_doc" method="post">
            @csrf
            <input type="text" name="title" id="title" placeholder="Titulo da postagem">
            <button type="submit">Create Document</button>
        </form>

        <h3>Retrivie document</h3>
        <form action="/process_doc" method="post">
            @csrf
            <input type="text" name="google_docs" id="" placeholder="id do documento">
            <input type="text" name="title" id="title" placeholder="Titulo da postagem">
            <button type="submit">Inserir texto do documento</button>
        </form>
    </div>
@endsection
