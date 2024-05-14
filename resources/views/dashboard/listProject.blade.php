@extends('layouts.app')

@section('content')
@php
    use App\Models\Editor;
    use App\Models\Project;
    use Illuminate\Http\Request;

    $valorCodificado = request()->cookie('editor');
    $user = explode('+', base64_decode($valorCodificado));
    $projects = Project::all();
@endphp
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
    <h2 class="mt-4">Lista de projetos</h2>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome do Projeto</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->project_name }}</td>
                <td>
                    <button type="button" class="btn btn-info" onclick="updateProject('{{ $project->id }}')">Update</button>
                    <button type="button" class="btn btn-danger" onclick="deleteProject('{{ $project->id }}')">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function updateProject(projectId) {
        console.log("Update project with ID: " + projectId);
    }

    function deleteProject(projectId) {
        console.log("Delete project with ID: " + projectId);

        // URL da rota delete, substitua 'your-domain.com' pelo seu domínio real
        const url = `/project/${projectId}`;

        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok.');
        })
        .then(data => {
            console.log('Projeto deletado com sucesso:', data);
            Swal.fire({
                    title: 'Projeto deletado com sucesso',
                    //html: "",
                    confirmButtonText: 'Close'
                });
            // Faça algo após a exclusão, como atualizar a UI
            window.location.reload();
        })
        .catch(error => {
            console.error('Ocorreu um problema ao deletar o projeto:', error);
            Swal.fire({
                    title: 'Erro',
                    html: "Ocorreu um problema ao deletar o projeto",
                    confirmButtonText: 'Close'
                });
        });
    }

</script>
@endsection
