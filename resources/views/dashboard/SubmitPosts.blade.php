<!-- resources/views/dashboard/SubmitPosts.blade.php -->
@extends('layouts.app')

@php
  use App\Models\Editor;

  $valorCodificado = request()->cookie('Editor');
  $user=explode('+',base64_decode($valorCodificado));
  $post_configs= Editor::where('name',$user[0])->get();
  $post_contents=Editor::find($post_configs[0]->id); 
@endphp

@section('content')
    <div class="dashboard-content">
        <h1>Lista de posts e configurações</h1>
        <div class="search_bar">
          <form action="/search" method="get">
            <div><input type="text" name="query" id="query"><input type="submit" value="search"></div>
          </form>
        </div>
        <!-- Formulário de Cadastro de Usuário -->
        <input type="hidden" name="user_id" class="user_id" value={{$post_configs[0]->id}}>
        @foreach($post_contents->postContents as $config)

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
            <button type="button" class="generateContentBtn btn btn-primary" id="generateContentBtn">Gerar</button>
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
      const user_id= document.querySelector('.user_id')
      




      document.addEventListener('DOMContentLoaded', function () {
      const generateContentButtons = document.querySelectorAll('.create_content');
      const postButton= document.querySelectorAll(".post_wp");
      const generateButton=document.querySelector('.generateContentBtn');
      let data_id;
      let Theme;
      console.log(generateButton);


      generateContentButtons.forEach((button,i) => {
  
        button.addEventListener('click', function () {
          const modal = document.querySelector('.modal');
          modal.classList.add('show');
          modal.style.display = 'block';
          document.body.classList.add('modal-open');

            data_id = button.closest('.container').getAttribute('data-id');
            Theme = button.closest('.container').querySelector('.theme').innerText;
          });

        });


        generateButton.addEventListener('click',async ()=>{
          const loading=document.createElement('div');
          const loadingSVG = `
                    <svg width="40" height="40" viewbox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
            <circle cx="20" cy="20" fill="none" r="10" stroke="#000" stroke-width="2">
              <animate attributeName="r" from="8" to="20" dur="1.5s" begin="0s" repeatCount="indefinite"/>
              <animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0s" repeatCount="indefinite"/>
            </circle>
          <!--   <circle cx="20" cy="20" fill="#383a36" r="10"/> -->
          </svg>
          
          `
          loading.innerHTML=loadingSVG;

          const modalDialog = document.querySelector('.modal-dialog');
          modalDialog.appendChild(loading);

          try {
            const query = await fetch('/gpt_query', {
                method: 'POST',
                body: JSON.stringify({
                    id: data_id,
                    topic: Theme,
                    languages: languages,  
                    style: writing_style,
                    writing_tone: writing_tone,
                    sections: sections,
                    paragraphs: paragraphs,
                    _token: csrfToken
                }),
                headers: { "Content-Type": "application/json" }
            });
        
        // Remove o SVG de loading após a conclusão da query
        
        if(query.ok){
          Swal.fire({
            title: 'Content Sucefully created',
            text: 'Do you want to continue',
            icon: 'success',
            confirmButtonText: 'continue'
          })
        }else{
          Swal.fire({
            title: 'Error on Generate Content',
            text: 'Do you want to continue',
            icon: 'error',
            confirmButtonText: 'continue'
          })
        }
        modalDialog.removeChild(loading);
        
        // Aqui você pode adicionar código para lidar com a resposta da query, se necessário
    } catch (error) {
        console.error('Ocorreu um erro:', error);
        Swal.fire({
            title: 'Error on the process',
            text: 'Do you want to continue',
            icon: 'error',
            confirmButtonText: 'continue'
          })
        // Se ocorrer um erro, é importante remover o SVG de loading para evitar confusão
        document.body.removeChild(loadingSVG);
    }
})



      postButton.forEach((button,i)=>{
        const container = button.closest('.container'); // Encontra o contêiner mais próximo ao botão clicado
        const data_id = container.getAttribute('data-id'); // Obtém o data-id do contêiner 
        const loading= document.createElement('span');
        
        
        loading.innerHTML='loading....'
        button.addEventListener('click',async()=>{
          button.insertAdjacentElement("beforebegin", loading);
          const domain=document.querySelectorAll('.domain')[i];
          console.log(domain.innerText);
          const query= await fetch('/post_content',{
            method:'POST',
            body:JSON.stringify({
               id:data_id,
               user_id:user_id.value,
               domain:domain.innerText,
              _token:csrfToken
            }),
            headers:{"Content-Type":"application/json"}
          })

          if(query.ok){
            Swal.fire({
            title: 'Post sucefully created on wordpress',
            text: 'Do you want to continue',
            icon: 'success',
            confirmButtonText: 'continue'
          })
            container.remove(loading);
          }else{
            Swal.fire({
            title: 'Error on the process',
            text: 'Do you want to continue',
            icon: 'error',
            confirmButtonText: 'continue'
          })
            button.remove(loading);
          }
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
