@extends('layouts.app')

@php  
    use Illuminate\Http\Request;

    $valorCodificado = request()->cookie('Editor');
    $user=explode('+',base64_decode($valorCodificado));

@endphp

@section('content')
<div class="col-md-6 offset-md-3 mt-5">
    <div class="card card-medium">
        <div class="card-body">
            <form action="{{ route('saveWpCredential') }}" method="post" class="needs-validation" novalidate>
                @csrf

                <div class="form-group">
                    <label for="login">Login do WordPress</label>
                    <input type="text" class="form-control" name="wp_login" id="login" placeholder="Digite seu login do WordPress" required>
                </div>

                <div class="form-group">
                    <label for="password">Senha do Site</label>
                    <input type="text" class="form-control" name="wp_password" id="password" placeholder="Digite a senha do site" required>
                </div>

                <div class="form-group">
                    <label for="domain">Domínio</label>
                    <input type="text" class="form-control" name="wp_domain" id="domain" placeholder="Digite o domínio" required>
                </div>

                <input type="hidden" name="user" value="{{ $user[0] }}">
                <br>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>
</div>

@endsection