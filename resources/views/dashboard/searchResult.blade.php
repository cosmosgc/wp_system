@extends('layouts.app')

@section('content')
    <h1>Busca: Resultados</h1>
    @foreach ($search as $config)
    <div class="container mt-5" data-id="{{$config->id}}">
          
        <table class="table">
          <thead>
            <tr>
              <th>Tema</th>
              <th>Palavra-chave</th>
              <th>Categoria</th>
              <th>Conteúdo do Post</th>
              <th>Inserir Imagem?</th>
              <th>Data de Criação</th>
              <th>Domain</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <!-- Aqui você pode iterar sobre os dados do seu banco de dados para preencher as linhas da tabela -->
            <!-- Exemplo de uma linha de dados -->
            <tr>
              <td class="theme">{{$config->theme}}</td>
              <td>{{$config->keyword}}</td>
              <td>{{$config->category}}</td>
              <td class="post-content">{{!empty($config->post_content)?'Sim':'Não'}}</td>
              <td>{{($config->insert_image==1)?'Sim':'Não'}}</td>
              <td>{{$config->created_at}}</td>
              <td class="domain">{{$config->domain}}</td>
              <td>
                <button class="btn btn-primary post_wp">Postar</button>
                <button class="btn btn-danger delete_config">Deletar</button>
                <button class="btn btn-success create_content">Gerar conteúdo</button>
              </td>
            </tr>
            <!-- Fim do exemplo de linha de dados -->
          </tbody>
        </table>
      </div>
        
    @endforeach
@endsection