@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <div class="card card-medium">
                <div class="card-body">
                    <h3>Criação de Documento</h3>
                    <form action="/create_doc" method="post">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" name="title" id="title" placeholder="Tema da da configuração do post">
                        </div>
                        <button type="submit" class="btn btn-primary">Criar documento</button>
                    </form>
                </div>
            </div>

            <div class="card card-medium mt-4">
                <div class="card-body">
                    <h3>Copiar informação do Documento</h3>
                    <form action="/process_doc" method="post">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" name="google_docs" id="google_docs" placeholder="Id do documento">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="title" id="title" placeholder="Tema da da configuração do post">
                        </div>
                        <button type="submit" class="btn btn-primary">Criar conteúdo via documento</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
