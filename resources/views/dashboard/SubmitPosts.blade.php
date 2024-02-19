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
                  <td class="theme">Tema A</td>
                  <td>Palavra-chave A</td>
                  <td>Categoria A</td>
                  <td class="post-content">Conteúdo do Post</td>
                  <td>Sim</td>
                  <td>2024-02-16 12:34:56</td>
                  <td>
                    <button class="btn btn-primary post_wp">Postar</button>
                    <button class="btn btn-danger delete_config">Deletar</button>
                    <button class="btn btn-success create_doc">Criar Documento</button>
                  </td>
                </tr>
                <!-- Fim do exemplo de linha de dados -->
              </tbody>
            </table>
          </div>
    </div>

    {{-- <script>
      const postButton= document.querySelector(".post_wp");
      const deleteButton= document.querySelector(".delete_config");
      const createDocButton= document.querySelector(".create_doc");
      const Theme=document.querySelector('.theme')
      const postContent=document.querySelector('.post-content')
      const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

      createDocButton.addEventListener('click',async ()=>{
        console.log(Theme.innerText)
        console.log(postContent.innerText)

        const docQuery=await fetch('/create_doc',{
          method:'POST',
          body:JSON.stringify({title:Theme.innerText, content:postContent.innerText, _token: csrfToken}),
          headers:{"Content-Type":"application/json"}
        })

        // Verifique se a solicitação foi bem-sucedida
      if (docQuery.ok) {
        // Obtenha a resposta como JSON
        const response = await docQuery.json();
        // Verifique se há uma URL de redirecionamento na resposta
        if (response.redirectUrl) {
          // Redirecione para a URL retornada na resposta
          window.location.href = response.redirectUrl;
        } else {
          console.error("URL de redirecionamento não encontrada na resposta.");
        }
      } else {
        console.error("Falha na solicitação para criar documento.");
      }

      })
    </script> --}}
@endsection
