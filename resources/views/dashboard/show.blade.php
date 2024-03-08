<!-- resources/views/dashboard/show.blade.php -->
@extends('layouts.app')
@php  
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    $valorCodificado = request()->cookie('editor');
    $user=explode('+',base64_decode($valorCodificado));
    $editors = DB::table('editors')->get();
@endphp

@section('content')
    <div class="dashboard-content">
        <h1>Bem-vindo {{$user[0]}} </h1>
        <!-- Conteúdo dinâmico -->
        <!-- Display the table -->
        <br>
        <h2>Usuários cadastrados</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sobrenome</th>
                    <th>CPF</th>
                    <th>CNPJ</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($editors as $editor)
                    <tr>
                        <td>{{ $editor->id }}</td>
                        <td>{{ $editor->name }}</td>
                        <td>{{ $editor->surname }}</td>
                        <td>{{ $editor->cpf }}</td>
                        <td>{{ $editor->cnpj }}</td>
                        <td>{{ $editor->email }}</td>
                        <td>
                            <form action="{{ route('editor.destroy', $editor->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
