<!-- resources/views/dashboard/SubmitPosts.blade.php -->
@extends('layouts.app')

@php
  use App\Models\Editor;

  $valorCodificado = request()->cookie('editor');
  $user=explode('+',base64_decode($valorCodificado));
  //dd($user);
  $post_configs= Editor::where('name',$user[0])->get();
  //dd($post_configs);
  if($post_configs->first() !=null){
    $post_contents=Editor::find($post_configs[0]->id);
  }

@endphp

@section('content')
<style>
        .editor_modal {
            height: 80vh;
            width: 70vw;
            max-width: 600px;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: rgb(0 0 0 / 63%);
            backdrop-filter: blur(4px);
            border-radius: 20px;
            display: none;
            color: #fff;
        }

        .editor_modal_content{
            padding: 30px;
            width: 90%;
        }

        .editor_modal_content>input{
            margin-bottom: 2%;
        }

        .open_editor_modal{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .upgrade_button {
            border-radius: 20px; /* Ajuste o valor conforme desejado */
            width: 25%;

        }

        .close_modal_button{
            position: absolute;
            top: 15px;
            right: 17px;
        }

    </style>
    <div class="dashboard-content">
        <h1>Lista de posts e configurações</h1>
        <div class="row justify-content-center">
          <div class="card card-medium">
            <div class="card-body">
              <div class="search_bar">
                <form action="/search" method="get">
                  <div class="input-group">
                    <input type="text" class="form-control" name="query" id="query" placeholder="Buscar por Dominio">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
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
                  <th>Agendado</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <!-- Aqui você pode iterar sobre os dados do seu banco de dados para preencher as linhas da tabela -->
                <!-- Exemplo de uma linha de dados -->
                <tr>
                  <td class="theme">{{$config->theme}}</td>
                  <td class="keyword">{{$config->keyword}}</td>
                  <td>{{$config->category}}</td>
                  <td class="post-content">{{!empty($config->post_content)?'Sim':'Não'}}</td>
                  <td>{{($config->insert_image==1)?'Sim':'Não'}}</td>
                  <td>{{$config->created_at}}</td>
                  <td class="domain">{{$config->domain}}</td>
                  <td class="schedule_date">{{$config->schedule_date}}</td>
                  <td>
                    <button class="btn btn-primary post_wp">Postar</button>
                    <button class="btn btn-danger delete_config">Deletar</button>
                    <button class="btn btn-success create_content">Gerar conteúdo</button>
                    <button class="btn btn-success update_content" onclick="open_modal('{{$config->id}}','{{$config}}')">Atualizar conteúdo</button>
                  </td>
                </tr>
                <!-- Fim do exemplo de linha de dados -->
              </tbody>
            </table>
            </div>

          </div>

          @endforeach
        </div>


    </div>
    <div class="editor_modal">
        <div class="editor_modal_content">
            <input disabled type="text" name="post_id" id="post_id" class="form-control" placeholder="0">
            <input type="text" name="Tema" id="Tema" class="form-control" placeholder="Tema">
            <input type="text" name="Palavra-chave" id="Palavra_chave" class="form-control" placeholder="Palavra-chave">
            <input type="text" name="Categoria" id="Categoria" class="form-control" placeholder="Categoria">
            <input type="text" name="Conteúdo do Post" id="post_content" class="form-control" placeholder="Conteúdo do Post">
            <input type="text" name="Inserir Imagem" id="Inserir_Imagem" class="form-control" placeholder="Inserir Imagem">
            <input type="text" name="domain" id="domain" class="form-control" placeholder="Dominío">
            <input type="hidden" name="id" id="id">
        </div>
        <button class="btn btn-primary upgrade_button">Atualizar</button>
        <button class="btn btn-danger close_modal_button">X</button>
    </div>
    <script>
            const modal= document.querySelector(".editor_modal");
            const closeModalButton=document.querySelector(".close_modal_button");
            const upgradeButton=document.querySelector(".upgrade_button")

            const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;


            //pegar listas
            let id = document.querySelectorAll('.config_id');
            const theme =document.querySelectorAll('.theme');
            const keyword =document.querySelectorAll('.keyword');
            const category =document.querySelectorAll('.category');
            const post_content =document.querySelectorAll('.post_content');
            const insert_image =document.querySelectorAll('.insert_image');
            const domain =document.querySelectorAll('.domain');

            //pegar modal
            let _post_id=document.getElementById("post_id");
            let _theme=document.getElementById("Tema");
            let _keyword=document.getElementById("Palavra_chave");
            let _category=document.getElementById("Categoria");
            let _post_content=document.getElementById("post_content");
            let _insert_image=document.getElementById("Inserir_Imagem");
            let _domain=document.getElementById("domain");

            let selected_id = 0;

            const update=document.querySelectorAll('.update_content');
            update.forEach((e,i)=>{
                e.addEventListener('click',()=>{
                    open_modal(i);
                })
            })
            upgradeButton.addEventListener('click',async ()=>{

                let data={
                    id:selected_id,
                    theme:_theme.value,
                    keyword:_keyword.value,
                    category:_category.value,
                    post_content:_post_content.value,
                    insert_image:_insert_image.value,
                    domain:_domain.value,
                    _token:csrfToken
                };
                console.log(data);

                const updateQuery= await fetch('/update_post',{
                    method:'PUT',
                    body:JSON.stringify({
                        id:selected_id,
                        theme:_theme.value,
                        keyword:_keyword.value,
                        category:_category.value,
                        post_content:_post_content.value,
                        insert_image:_insert_image.value,
                        domain:_domain.value,
                        _token:csrfToken
                    }),

                    headers:{"Content-Type":"application/json"}

                })

                if(updateQuery.ok){
                    alert('atualização feita com sucesso');
                }else{
                    alert('atualização falhou');
                }
            })

            closeModalButton.addEventListener('click',()=>{
                modal.classList.remove('open_editor_modal');
            })

            function open_modal(i = 0, data = null) {
                let parsedData = JSON.parse(data);
                console.log(parsedData);

                modal.classList.add('open_editor_modal');

                _theme.value = parsedData.theme;
                _keyword.value = parsedData.keyword;
                _category.value = parsedData.category;
                _post_content.value = parsedData.post_content ? 'Sim' : 'Não';
                _insert_image.value = parsedData.insert_image == 1 ? 'Sim' : 'Não';
                _domain.value = parsedData.domain;
                _post_id.value = parsedData.id;
                selected_id = parsedData.id;
            }


        </script>


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

      //const deleteButton= document.querySelector(".delete_config");
      const createDocButton= document.querySelector(".create_doc");
      const postContent=document.querySelector('.post-content')
      const languages=document.getElementById("languages").value;
      const writing_style=document.getElementById("style").value;
      const writing_tone=document.getElementById('writing_tone').value;
      const sections=document.getElementById('sections').value;
      const paragraphs= document.getElementById('paragraphs').value;
      //const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
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
          const keyword=document.querySelectorAll('.keyword')[i]
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
          const test= await query.json()

          const query_2= await fetch('/update_yoaust',{
            method:'POST',
            body:JSON.stringify({
               id:test,
               domain:domain.innerText,
               keyword:keyword.innerText,
              _token:csrfToken
            }),
            headers:{"Content-Type":"application/json"}
          })

          console.log(test)

          if(query.ok && query_2.ok){
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


const deletebutton= document.querySelectorAll(".delete_config")




deletebutton.forEach((e,i)=>{
  e.addEventListener('click',async ()=>{
    data_id=document.querySelector(".container").getAttribute("data-id");
    console.log(data_id);
    const deletion_query= await fetch('/remove_config',{
      method:'DELETE',
      body:JSON.stringify({
         id:data_id,
        _token:csrfToken
      }),
      headers:{"Content-Type":"application/json"}
    })

    if(deletion_query.ok){
      Swal.fire({
            title: 'Configuração removida com sucesso',
            text: 'Do you want to continue',
            icon: 'success',
            confirmButtonText: 'continue'
          })
          location.reload()
    }else{
      Swal.fire({
            title: 'Erro ao remover configuração',
            text: 'Do you want to continue',
            icon: 'error',
            confirmButtonText: 'continue'
          })

          location.reload();
    }
  })
})

</script>



<style>
  /* Estilo para o efeito de blur */
  .modal.show {
    backdrop-filter: blur(4px);
  }
</style>
@endsection
