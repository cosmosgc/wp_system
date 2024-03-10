@extends('layouts.app')

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
    <h1>Busca: Resultados</h1>
    @foreach ($search as $config)
    <div class="container mt-5" data-id="{{$config->id}}">

        <table class="table">
          <thead>
            <tr>
            <th>id</th>
              <th>Tema</th>
              <th>Palavra-chave</th>
              <th>Categoria</th>
              <th>Conteúdo do Post</th>
              <th>Inserir Imagem?</th>
              <th>Data de Criação</th>
              <th>Dominio</th>
              <th>Agendado</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <!-- Aqui você pode iterar sobre os dados do seu banco de dados para preencher as linhas da tabela -->
            <!-- Exemplo de uma linha de dados -->
            <tr>
            <td class="config_id">{{$config->id}}</td>
              <td class="theme">{{$config->theme}}</td>
              <td class="keyword">{{$config->keyword}}</td>
              <td class="category">{{$config->category}}</td>
              <td class="post_content">{{!empty($config->post_content)?'Sim':'Não'}}</td>
              <td class="insert_image">{{($config->insert_image==1)?'Sim':'Não'}}</td>
              <td class="created_at">{{$config->created_at}}</td>
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


    @endforeach
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
@endsection
