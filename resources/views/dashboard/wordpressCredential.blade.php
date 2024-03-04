@extends('layouts.app')

@php  
    use Illuminate\Http\Request;

    $valorCodificado = request()->cookie('Editor');
    $user=explode('+',base64_decode($valorCodificado));

@endphp

@section('content')
<form action="{{route('saveWpCredential')}}" method="post">
    @csrf <!-- {{ csrf_field() }} -->
    <input type="text" name="wp_login" id="login" placeholder="digite seu login do wordpress">
    <input type="text" name="wp_password" id="password" placeholder="Digite a senha do site">
    <input type="text" name="wp_domain" id="domain", placeholder="digite o dominio">
    <input type="hidden" name="user" value="{{$user[0]}}">
    <input type="submit" value="Enviar">
</form>
@endsection