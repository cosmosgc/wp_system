@extends('layouts.app')

@section('content')
<form action="{{route('createProject')}}" method="post">
    @csrf
    <input type="text" name="project_name" id="project_name">
    <input type="submit" value="enviar">
</form>
@endsection