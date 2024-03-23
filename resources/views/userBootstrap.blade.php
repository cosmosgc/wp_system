<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Criaão de Usuario root</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Criação de Usuário Raiz</div>

                <form method="POST" action="{{route('processEditor')}}" class="card-body">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Nome</label>
                            <input id="name" type="text" class="form-control" name="name"  required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        </div>

                        <button type="submit" class="submit btn btn-primary">Criar Usúario</button>
                   
                </form>
            </div>
        </div>
    </div>
</div>