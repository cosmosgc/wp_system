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
<!-- Modal -->
<div class="modal fade" id="updateProjectModal" tabindex="-1" aria-labelledby="updateProjectModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProjectModalLabel">Project Items</h5>
                <button type="button" class="btn-close" onclick="hideModal()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table" id="projectItemsTable">
                    <thead>
                        <tr>
                            <th>Theme</th>
                            <th>Keyword</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Conteúdo dinâmico será adicionado aqui -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="hideModal()">Close</button>
            </div>
        </div>
    </div>
</div>


</div>

<script>
    function updateProject(projectId) {
        console.log("Update project with ID: " + projectId);

        // URL da rota GET para listar itens do projeto
        const url = `/project/${projectId}`;

        fetch(url, {
            method: 'GET',
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
            console.log('Project items fetched successfully:', data);

            const tbody = document.querySelector('#projectItemsTable tbody');
            tbody.innerHTML = ''; // Limpar conteúdo existente

            // Adicionar novos dados à tabela
            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.theme}</td>
                    <td>${item.keyword}</td>
                    <td>${item.created_at}</td>
                    <td><button type="button" class="btn btn-danger" onclick="removeItemFromProject('${item.id}')">Remove</button></td>
                `;
                tbody.appendChild(row);
            });

            // Exibir o modal
            document.getElementById('updateProjectModal').style.display = 'block';
            document.getElementById('updateProjectModal').classList.add('show');
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            Swal.fire({
                    title: 'Erro',
                    html: "Ocorreu um problema ao encontrar itens do projeto",
                    confirmButtonText: 'Close'
                });
        });
    }
    function hideModal() {
        const modal = document.getElementById('updateProjectModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 200); // Tempo para animação (ajuste conforme necessário)
    }

    function removeItemFromProject(itemId) {
        console.log("Remove item from project with ID: " + itemId);

        const url = `/project/${itemId}`;

        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                action: 'remove_item',  // Um exemplo de payload para especificar a ação
                item_id: itemId
            })
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok.');
        })
        .then(data => {
            console.log('Item removed from project successfully:', data);
            swal.fire({
                title: "Success!",
                text: "Item removed successfully!",
                icon: "success"
            }).then(() => {
                // Remover o item da tabela
                const row = document.querySelector(`#projectItemsTable tbody tr td button[onclick="removeItemFromProject('${itemId}')"]`).closest('tr');
                row.remove();
            });
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            Swal.fire({
                    title: 'Erro',
                    html: "Ocorreu um problema ao deletar o item do projeto: " + error,
                    confirmButtonText: 'Close'
                });
        });
    }

    function deleteProject(projectId) {
        console.log("Delete project with ID: " + projectId);

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
