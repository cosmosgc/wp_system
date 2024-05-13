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
                    <button type="button" class="btn btn-info" onclick="updateProject({{ $project->id }})">Update</button>
                    <button type="button" class="btn btn-danger" onclick="deleteProject({{ $project->id }})">Delete</button>
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
    }
</script>
@endsection
