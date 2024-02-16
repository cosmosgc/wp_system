<!-- resources/views/dashboard/SubmitPosts.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="dashboard-content">
        <h1>Lista de posts e configurações</h1>
        <!-- Formulário de Cadastro de Usuário -->
        <div class="container mt-5">
            <h2>Tabela de Dados</h2>
            <table class="table">
              <thead>
                <tr>
                  <th>Tema</th>
                  <th>Palavra-chave</th>
                  <th>Categoria</th>
                  <th>Conteúdo do Post</th>
                  <th>Inserir Imagem?</th>
                  <th>Data de Criação</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <!-- Aqui você pode iterar sobre os dados do seu banco de dados para preencher as linhas da tabela -->
                <!-- Exemplo de uma linha de dados -->
                <tr>
                  <td>Tema A</td>
                  <td>Palavra-chave A</td>
                  <td>Categoria A</td>
                  <td>Conteúdo do Post</td>
                  <td>Sim</td>
                  <td>2024-02-16 12:34:56</td>
                  <td>
                    <button class="btn btn-primary">Postar</button>
                    <button class="btn btn-danger">Deletar</button>
                    <button class="btn btn-success">Criar Documento</button>
                  </td>
                </tr>
                <!-- Fim do exemplo de linha de dados -->
              </tbody>
            </table>
          </div>
    </div>
@endsection
