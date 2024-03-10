@extends('layouts.app')

@section('content')

<div class="dashboard-content">
    <br>
    <h2>Tokens da Open-ia Cadastrados</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nome do Editor</th>
                <th>token</th>
                <th>Ações</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($editor as $edit)
                <tr>
                    <td class="id">{{ $edit->name }}</td>
                    <td class="name">{{$edit->iaCredentials->open_ai}}</td>
                    <td>
                        <form action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Excluir</button>
                        </form>

                        <button class="update">Alterar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection