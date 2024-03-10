@extends('layouts')

@section('content')

<div class="dashboard-content">
    <br>
    <h2>Tokens da Open-ia Cadastrados</h2>
    <table class="table">
        <thead>
            <tr>
                <th>nome</th>
                 <th>{{ csrf_token() }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($editors as $editor)
                <tr>
                    <td class="id">{{ $editor->id }}</td>
                    <td class="name">{{ $editor->name }}</td>
                    <td>
                        <form action="{{ route('editor.destroy', $editor->id) }}" method="POST">
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