<!-- resources/views/dashboard/SubmitPosts.blade.php -->
@extends('layouts.app')

@php
  use App\Models\Wp_post_content;
  $post_configs= Wp_post_content::all();
@endphp

@section('content')
    <div class="dashboard-content">
        <h1>Lista de posts e configurações</h1>
        <!-- Formulário de Cadastro de Usuário -->
        @foreach($post_configs as $config)

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

    </div>


    <div class="modal fade" id="generateContentModal" tabindex="-1" role="dialog" aria-labelledby="generateContentModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="generateContentModalLabel">Gerar Conteúdo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="generateContentForm">
              <div class="form-group">
                <label for="languages">Idioma</label>
               <select name="language" id="languages">
                <option value="english">English</option>
                <option value="portuguese">Portuguese</option>
               </select>
              </div>
              <div class="form-group">
                <label for="style">Estilo de escrita</label>
                <select name="style" id="style">
                  <option value="blog">Blog</option>
                  <option value="first_person">First Person</option>
                </select>
              </div>
              <div class="form-group">
                <label for="writing_tone">Tom de escrita</label>
                <select name="tone" id="writing_tone">
                  <option value="casual">Casual</option>
                  <option value="eloquent">Eloquent</option>
                </select>
              </div>
              <div class="form-group">
                <label for="sections">Número de Seções</label>
                <input type="number" class="form-control" id="sections" name="sections">
              </div>
              <div class="form-group">
                <label for="paragraphs">Parágrafos por Seção</label>
                <input type="number" class="form-control" id="paragraphs" name="paragraphs">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" id="generateContentBtn">Gerar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      
      const deleteButton= document.querySelector(".delete_config");
      const createDocButton= document.querySelector(".create_doc");
      const postContent=document.querySelector('.post-content')
      const languages=document.getElementById("languages").value;
      const writing_style=document.getElementById("style").value;
      const writing_tone=document.getElementById('writing_tone').value;
      const sections=document.getElementById('sections').value;
      const paragraphs= document.getElementById('paragraphs').value;
      const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
      




      document.addEventListener('DOMContentLoaded', function () {
      const generateContentButtons = document.querySelectorAll('.create_content');
      const postButton= document.querySelectorAll(".post_wp");


      generateContentButtons.forEach((button) => {
        button.addEventListener('click', function () {
          const modal = document.querySelector('.modal');
          modal.classList.add('show');
          modal.style.display = 'block';
          document.body.classList.add('modal-open');
        });

        const generateButton=document.getElementById('generateContentBtn');
        const data_id=document.querySelector('.container').getAttribute('data-id');
        const Theme=document.querySelector('.theme').innerText
        console.log(Theme);

        generateButton.addEventListener('click',async ()=>{
          console.log(data_id);
          console.log(Theme);
          console.log(languages);
          console.log(writing_style);
          console.log(writing_tone);
          console.log(sections);
          console.log(paragraphs);


          const query= await fetch('/gpt_query',{
            method:'POST',
            body:JSON.stringify({
              id:data_id,
              topic:Theme,
              languages:languages,
              style:writing_style,
              writing_tone:writing_tone,
              sections:sections,
              paragraphs:paragraphs,
              _token:csrfToken
            }),
            headers:{"Content-Type":"application/json"}
          })
        })
      });


      postButton.forEach(button=>{
        const data_id=document.querySelector('.container').getAttribute('data-id');
        button.addEventListener('click',async()=>{
          const query= await fetch('/post_content',{
            method:'POST',
            body:JSON.stringify({
               id:data_id,
              _token:csrfToken
            }),
            headers:{"Content-Type":"application/json"}
          })
        })
      })

});



// Fechar o modal ao clicar no botão Fechar
document.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', () => {
        const modal = document.querySelector('.modal.show');
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
    });
});

</script>


<style>
  /* Estilo para o efeito de blur */
  .modal.show {
    backdrop-filter: blur(4px);
  }
</style>
@endsection
