<!-- resources/views/dashboard/show.blade.php -->
@extends('layouts.app')
@php  
    use Illuminate\Http\Request;

    $valorCodificado = request()->cookie('editor');
    $user=explode('+',base64_decode($valorCodificado));

@endphp

@section('content')
    <div class="dashboard-content">
        <h1>Bem-vindo {{$user[0]}} </h1>
        <!-- Conteúdo dinâmico -->
        
    </div>
@endsection