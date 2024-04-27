@extends('layouts.app')

@php
    use App\Models\Editor;

    $valorCodificado = request()->cookie('editor');
    $user=explode('+',base64_decode($valorCodificado));
    $post_configs= Editor::where('name',$user[0])->get();
@endphp

@section('content')
    <div class="dashboard-content">
        <form action="/submit_file" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="csv_file" id="">
            <input type="hidden" name="docType" value="site_register">
            <input type="hidden" name="user_id" value="{{$post_configs[0]->id}}">
            <input type="submit" value="enviar">
        </form>
    </div>
@endsection