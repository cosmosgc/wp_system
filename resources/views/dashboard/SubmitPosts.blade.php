<!-- resources/views/dashboard/SubmitPosts.blade.php -->
@extends('layouts.app')

@php
  use App\Models\Editor;

  $valorCodificado = request()->cookie('editor');
  $user=explode('+',base64_decode($valorCodificado));
  $post_configs = Editor::where('name',$user[0])->get();
  $searchParam = request()->input('query');
  if($post_configs->first() !=null){
    $post_contents=Editor::find($post_configs[0]->id);
    if(!empty($search)){
        $post_contents->postContents=$search;
    }

    else{
        $searchParam = '';
    }


    $post_content_objects = [];

    $post_contents->postContents->each(function ($config) use (&$post_content_objects) {
        if (!empty($config->post_content) && isset($config->post_content)) {
            $post_content_objects[] = (object) [
                'id' => $config->id,
                'post_content' => $config->post_content
            ];
            $config->post_content = true;
        }
    });

  }

@endphp

@section('content')
<style>
        .editor_modal, .batch_modal {
            height: 80vh;
            width: 90vw;
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
            position: fixed;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            flex-direction: column;
            overflow: auto;
        }
        @media(min-width:700px){
            .open_editor_modal{
                left: 530px;
                max-width: 500px;
            }
        }

        .editor_list_flex {
            width: 200px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px; /* Optional: adds some spacing between items */
        }

        .editor_list_flex .btn {
            flex: 1 1 calc(33.333% - 10px); /* Three items per row with space between */
            box-sizing: border-box;
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
        tr.loading {
            background: #8d8d8d54;
            animation: pulse 1s infinite alternate;
        }
        @keyframes pulse {
            0% {
                background-color: #8d8d8d54;
            }
            100% {
                background-color: #8d8d8d;
            }
        }

          /* Estilo para o efeito de blur */
      .modal.show {
        backdrop-filter: blur(4px);
      }

    </style>
    <div class="dashboard-content">
        @if(!empty($search))
        <h1>Resultados da busca</h1>
        @else
        <h1>Lista de posts e configurações</h1>
        @endif
        <div class="row justify-content-center">
          <div class="card card-medium">
            <div class="card-body">
              <div class="search_bar">
                <form action="/list_content" method="get">
                  <div class="input-group">
                    <input type="text" class="form-control" name="query" id="query" placeholder="Buscar por Nome do post ou Dominio" value="{{$searchParam}}">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                  </div>
                  <!-- <div class="d-flex justify-content-around">
                        <div class="form-group">
                            <label for="projects">Selecione o projeto</label>
                            <select name="projects" id="projects" class="form-control">
                                <option value="">Sem projeto</opt>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" @if(request('projects') == $project->id) selected @endif>
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="custom_filters">Filtros</label>
                            <select name="custom_filters" id="custom_filters" class="form-control">
                                <option value="Não publicado" @if(request('custom_filters') == "Não publicado") selected @endif>Não publicado</option>
                                <option value="Sem conteudo" @if(request('custom_filters') == "Sem conteudo") selected @endif>Sem conteúdo</option>
                                <option value="">Sem filtro</option>
                            </select>
                        </div>
                  </div> -->

                </form>
              </div>
            </div>
          </div>


          <input type="hidden" name="user_id" class="user_id" value="{{isset($post_configs[0]->id)?$post_configs[0]->id:0}}">

            <div class="row justify-content-center">

                <div class="col-md-2 mb-3" style="display: flex; justify-content: flex-end;">
                    <button class="btn btn-success btn-block" onclick="batch_generate()">
                        <i class="fas fa-file"></i> Gerar conteúdo em lote
                    </button>
                </div>
                <div class="col-md-2 mb-3" style="display: flex; justify-content: center;">
                    <button class="btn btn-primary btn-block" onclick="batch_post()">
                        <i class="fas fa-upload"></i> Postar em lote
                    </button>
                </div>
                <div class="col-md-2 mb-3" style="display: flex; justify-content: flex-start;">
                    <button class="btn btn-danger btn-block" onclick="batch_delete()">
                        <i class="fas fa-trash-alt"></i> Deletar em lote
                    </button>
                </div>
                <div class="col-md-2 mb-3" style="display: flex; justify-content: flex-start;">
                    <button class="btn btn-primary btn-block" onclick="batch_doc()">
                        <i class="fab fa-google"></i> Criar documentos em lote
                    </button>
                </div>
                <div class="col-md-2 mb-3" style="display: flex; justify-content: flex-start;">
                    <button class="btn btn-primary btn-block" onclick="batch_csv()">
                        <i class="fas fa-table"></i> Exportar para CSV
                    </button>
                </div>

            </div>

            <div class="row mt-2 progress-bar-parent" style="display:none;">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                        <span class="progress-label">0%</span>
                    </div>
                </div>
            </div>
            <!-- talvez mover esse filtro para o input de pesquisa -->
            <div class="row mt-4 justify-content-between">
                <div class="col-md-5 col-lg-5">
                    <div class="UnpublishedField">
                    <form action="/list_content" class="d-flex justify-content-around" method="get">
                        <div class="form-group  flex-column d-flex justify-content-between">
                            <label for="projects">Selecione o projeto</label>
                            <select name="projects" id="projects" class="form-control">
                                <option value="">Sem projeto</opt>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" @if(request('projects') == $project->id) selected @endif>
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  flex-column d-flex justify-content-between">
                            <label for="custom_filters">Filtros</label>
                            <select name="custom_filters" id="custom_filters" class="form-control">
                                <option value="">Sem filtro</option>
                                <option value="Não publicado" @if(request('custom_filters') == "Não publicado") selected @endif>Não publicado</option>
                                <option value="Sem conteudo" @if(request('custom_filters') == "Sem conteudo") selected @endif>Sem conteúdo</option>
                            </select>
                        </div>

                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </form>

                    </div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="form-group d-flex flex-column mb-3">
                        <label for="statusFilter">Status</label>
                        <select id="statusFilter" class="form-control">
                            <option value="">Todos</option>
                            <option value="Sem conteúdo">Sem conteúdo</option>
                            <option value="Não publicado">Não publicado</option>
                        </select>
                    </div>
                </div>

            </div>




          <div class="container">

            <table class="table" id="post_list_table">
              <thead>

                <tr>
                    <th><input type="checkbox" id="selectAllCheckbox" onclick="selectAllCheckbox()"></th>
                  <th>Tema</th>
                  <th>Palavra-chave</th>
                  <th>Categoria</th>
                  <th>Conteúdo do Post</th>
                  <th>Inserir Imagem?</th>
                  <th>Data de Criação</th>
                  <th>Domain</th>
                  <th>Agendado</th>
                  <th>Status</th>
                  <th>Url do Post</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <!-- Aqui você pode iterar sobre os dados do seu banco de dados para preencher as linhas da tabela -->
                <!-- Exemplo de uma linha de dados -->

                @foreach($post_contents->postContents as $config)
                <tr>
                  <td>
                      <input class="form-check-input config_id" type="checkbox"  data-id="{{$config->id}}" data-theme="{{$config->theme}}" data-drive_folder={{$config->gdrive_document_url}}>
                  </td>


                  <td class="theme">{{$config->theme}}</td>
                  <td class="keyword">{{$config->keyword}}</td>
                  <td class="category">{{$config->category}}</td>
                  <td id="content" class="post-content" onclick='openPost("{{$config->id}}");'><a href="#">{{!empty($config->post_content)?'Sim':'Não'}}</a></td>
                  <td>
                        @if($config->post_image)
                            sim
                            <!-- <img src="{{ Storage::url('public/' . $config->post_image) }}" alt="Image" loading="lazy" style="max-width: 100px; height: auto;"> -->
                        @else
                            Não
                        @endif
                    </td>

                  <td>{{$config->created_at}}</td>
                  <td class="domain">{{$config->domain}}</td>
                  <td class="schedule_date">{{$config->schedule_date}}</td>
                  <td id="tableStatus" class="status">{{$config->status}}</td>
                  <td class="post_url">
                    @if(!empty($config->post_url))
                        <a href="{{$config->post_url}}">Acessar</a>
                    @endif
                  </td>
                  <td class="editor_list_flex">
                    <!-- Postar Button with Font Awesome icon and alt attribute -->
                    <button class="btn btn-primary post_wp" data-toggle="tooltip" data-placement="top" title="Postar">
                    <i class="fas fa-upload"></i>
                    </button>

                    <!-- Deletar Button with Font Awesome icon and alt attribute -->
                    <button class="btn btn-danger delete_config" data-toggle="tooltip" data-placement="top" title="Deletar">
                    <i class="fas fa-trash"></i>
                    </button>
                    <!-- Gerar conteúdo Button with Font Awesome icon and alt attribute -->
                    <button onclick="generate_post([`{{$config->theme}}`], `{{$config->id}}`, this, true)" class="btn btn-success create_content" data-toggle="tooltip" data-placement="top" title="Gerar conteúdo">
                    <i class="fas fa-file"></i>
                    </button>

                    <!-- Atualizar conteúdo Button with Font Awesome icon, alt attribute, and popover -->
                    <button class="btn btn-success update_content" data-toggle="popover" data-placement="top" title="Atualizar conteúdo" data-content="Clique para atualizar o conteúdo" onclick="open_modal(`{{$config->id}}`,`{{$config}}`)">
                    <i class="fas fa-wrench"></i>
                    </button>
                    <button class="btn btn-primary gdrive_doc" data-toggle="popover" data-placement="top" title="Criar doc" data-content="Clique para salvar em documento google drive" onclick="create_gdoc(`{{$config->theme}}`,`{{$config->id}}`, '{{$config->gdrive_document_url}}', this)">
                    <i class="fab fa-google"></i>
                    </button>
                    <button class="btn btn-primary gdrive_doc_input" data-toggle="popover" data-placement="top" title="Consumir doc" data-content="Clique para carregar em documento google drive" onclick="get_gdoc(`{{$config->theme}}`,`{{$config->id}}`,'', this)">
                        <i class="fa fa-folder"></i>
                    </button>

                  </td>
                </tr>
                @endforeach
                <!-- Fim do exemplo de linha de dados -->
              </tbody>
            </table>
            </div>




          </div>
        </div>


    </div>
    <div class="editor_modal">
        <div class="editor_modal_content">
            <input disabled type="text" name="post_id" id="post_id" class="form-control" placeholder="0">
            <input type="hidden" name="id" id="id">
            <input type="text" name="_theme" id="_theme" class="form-control" placeholder="Theme">
            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Keyword">
            <input type="text" name="category" id="category" class="form-control" placeholder="Category">
            <input type="text" name="domain" id="domain" class="form-control" placeholder="Domain">

            <input type="text" name="insert_image" id="insert_image" class="form-control" placeholder="Insert Image">
            <input type="text" name="internal_link" id="internal_link" class="form-control" placeholder="Internal Link">
            <input type="text" name="post_image" id="post_image" class="form-control" placeholder="Post Image">
            <input type="text" name="schedule_date" id="schedule_date" class="form-control" placeholder="Schedule Date">
            <input type="text" name="status" id="status" class="form-control" placeholder="Status">

            <input type="text" name="anchor_1" id="anchor_1" class="form-control" placeholder="Anchor 1">
            <input type="text" name="anchor_2" id="anchor_2" class="form-control" placeholder="Anchor 2">
            <input type="text" name="anchor_3" id="anchor_3" class="form-control" placeholder="Anchor 3">
            <input type="text" name="do_follow_link_1" id="do_follow_link_1" class="form-control" placeholder="Do Follow Link 1">
            <input type="text" name="do_follow_link_2" id="do_follow_link_2" class="form-control" placeholder="Do Follow Link 2">
            <input type="text" name="do_follow_link_3" id="do_follow_link_3" class="form-control" placeholder="Do Follow Link 3">
            <input type="text" name="created_at" id="created_at" class="form-control" placeholder="Created At" disabled>
            <input type="text" name="updated_at" id="updated_at" class="form-control" placeholder="Updated At" disabled>
            <input type="text" name="url_link_1" id="url_link_1" class="form-control" placeholder="URL Link 1">
            <input type="text" name="url_link_2" id="url_link_2" class="form-control" placeholder="URL Link 2">
            <input type="text" name="url_link_3" id="url_link_3" class="form-control" placeholder="URL Link 3">
            <input type="text" name="video" id="video" class="form-control" placeholder="video">
            <input type="text" name="editor_id" id="editor_id" class="form-control" placeholder="Editor ID" disabled>

        </div>
        <button class="btn btn-primary upgrade_button">Atualizar</button>
        <button class="btn btn-danger close_modal_button">X</button>
    </div>

<!-- DOTO: tenho que me livrar desses códigos e do Jquery -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css">

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- DataTables JS -->

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
<script>

let table = new DataTable('#post_list_table', {
    language: {
        url: 'https://cdn.datatables.net/plug-ins/2.0.7/i18n/pt-BR.json',
    },
    "searching": true,
    "columnDefs": [
            { "orderable": false, "targets": 0 } // Disable ordering for the first column
        ],
    pageLength: 500, // Set default page length to 500
    lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
});
$('#statusFilter').on('change', function() {
        let selectedValue = $(this).val();
        if(selectedValue == "Não publicado")
        {
            table.column(9).search(selectedValue).draw();
            table.column(4).search("").draw();
        }

        else if(selectedValue == "Sem conteúdo")
        {
            table.column(9).search("").draw();
            table.column(4).search("não").draw();
        }

        else if(selectedValue == "")
        {
            table.column(4).search("").draw();
            table.column(9).search("").draw();
        }

    });

    // Initially filter the table to show only "Não publicado"
    $('#statusFilter').val('').trigger('change');

</script>
<!-- o código que quero substituir acaba aqui -->

    <script>
        var postContents = [];

        function loadPosts(){
            // Loop through each item in $post_contents->postContents
            @foreach ($post_content_objects as $item)
                // Create a JavaScript object for each item
                var postItem = {
                    id: "{{ $item->id }}",
                    post_content: {!! json_encode($item->post_content) !!} // Make sure to encode HTML content properly
                };

                // Push the object to the postContents array
                postContents.push(postItem);
            @endforeach

            // Now you have the postContents array holding all the id and post_content values
            console.log(postContents);
        }

        loadPosts();

        function selectAllCheckbox(){
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const checkboxes = document.querySelectorAll('.form-check-input');
            // Get the value of the select all checkbox
            const isChecked = selectAllCheckbox.checked;

            // Loop through all checkboxes and set their checked state
            checkboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
            });
        }
        function getSelectedItems(condition ='loading', remove = false) {
          var checkboxes = document.querySelectorAll('.form-check-input');
          var selectedItems = [];

          checkboxes.forEach(function(checkbox) {
              if (checkbox.checked) {
                  var id = checkbox.getAttribute('data-id');
                  var theme = checkbox.getAttribute('data-theme');
                  var driver_folder=checkbox.getAttribute('data-drive_folder');

                  selectedItems.push({ id: id, theme: theme ,g_drive:driver_folder});
                  var parentTr = checkbox.closest('tr');
                  console.log(parentTr);
                  if(remove){
                    parentTr.classList.remove(condition);
                  }else{
                    parentTr.classList.add(condition);
                  }
              }
          });

          return selectedItems;
      }
        function separateThemesAndIDs(selectedItems) {
            var themes = [];
            var ids = [];
            var driver_folder_url=[]

            selectedItems.forEach(function(item) {
                themes.push(item.theme);
                ids.push(item.id);
                driver_folder_url.push(item.g_drive)
            });

            return { themes: themes, ids: ids, gdrive_document_url:driver_folder_url };
        }


        function openPost(id){
            // Find the post_content corresponding to the id
            var postContent = postContents.find(function(item) {
                return item.id === id;
            });

            // If postContent is found, display it using SweetAlert
            if (postContent) {
                Swal.fire({
                    title: 'Conteúdo do post',
                    html: postContent.post_content,
                    confirmButtonText: 'Close'
                });
            } else {
                // If postContent is not found, display an error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Post não encontrado!'
                });
            }
        }

    </script>



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
            let _editor_id = document.getElementById("editor_id");
            let _anchor_1 = document.getElementById("anchor_1");
            let _anchor_2 = document.getElementById("anchor_2");
            let _anchor_3 = document.getElementById("anchor_3");
            let _category = document.getElementById("category");
            let _created_at = document.getElementById("created_at");
            let _do_follow_link_1 = document.getElementById("do_follow_link_1");
            let _do_follow_link_2 = document.getElementById("do_follow_link_2");
            let _do_follow_link_3 = document.getElementById("do_follow_link_3");
            let _domain = document.getElementById("domain");
            let _id = document.getElementById("post_id");
            let _post_id = id;
            let _insert_image = document.getElementById("insert_image");
            let _internal_link = document.getElementById("internal_link");
            let _keyword = document.getElementById("keyword");
            let _post_content = document.getElementById("post_content");
            let _post_image = document.getElementById("post_image");
            let _schedule_date = document.getElementById("schedule_date");
            let _status = document.getElementById("status");
            let _theme = document.getElementById("_theme");
            let _updated_at = document.getElementById("updated_at");
            let _url_link_1 = document.getElementById("url_link_1");
            let _url_link_2 = document.getElementById("url_link_2");
            let _url_link_3 = document.getElementById("url_link_3");
            let _video = document.getElementById("video");


            let selected_id = 0;

            const update=document.querySelectorAll('.update_content');

            upgradeButton.addEventListener('click',async ()=>{

                let data={
                    id: _id.value,
                    editor_id: _editor_id.value,
                    anchor_1: _anchor_1.value,
                    anchor_2: _anchor_2.value,
                    anchor_3: _anchor_3.value,
                    category: _category.value,
                    created_at: _created_at.value,
                    do_follow_link_1: _do_follow_link_1.value,
                    do_follow_link_2: _do_follow_link_2.value,
                    do_follow_link_3: _do_follow_link_3.value,
                    domain: _domain.value,
                    insert_image: _insert_image.value,
                    internal_link: _internal_link.value,
                    keyword: _keyword.value,
                    post_image: _post_image.value,
                    schedule_date: _schedule_date.value,
                    status: _status.value,
                    theme: _theme.value,
                    updated_at: _updated_at.value,
                    url_link_1: _url_link_1.value,
                    url_link_2: _url_link_2.value,
                    url_link_3: _url_link_3.value,
                    video: _video.value,
                    _token: csrfToken
                };
                // console.log(data);

                const updateQuery= await fetch('/update_config',{
                    method:'PUT',
                    body: JSON.stringify(data),


                    headers:{"Content-Type":"application/json"}

                })

                if(updateQuery.ok){
                    alert('atualização feita com sucesso');
                    // console.log(updateQuery);
                }else{
                    alert('atualização falhou');
                    console.error(updateQuery.error)
                }
            })

            closeModalButton.addEventListener('click',()=>{
                modal.classList.remove('open_editor_modal');
            })


            async function batch_csv(){
                selected_items = getSelectedItems('loading');
                separatedData = separateThemesAndIDs(selected_items);
                const progressBar = document.querySelector('.progress-bar-parent');
                let errorItems = [];
                let completedItems = 0;
                const totalItems = Object.keys(separatedData.themes).length;
                progressBar.style.display = 'block';
                updateProgressBar(0);

                csv_data = [];
                for (const theme in separatedData.themes) {
                    const id = separatedData.ids[theme];
                    //console.log(drive_url);
                    //const loading_doc=selectedItems[theme]
                    console.log("exportando", [theme], [id]);
                    try {
                        csv_data.push(await get_post(id));
                    } catch (error) {
                        errorItems.push({ theme, id});
                    }
                    completedItems++;
                    const progress = Math.round((completedItems / totalItems) * 100);
                    let label = completedItems + "/" + totalItems;
                    updateProgressBar(progress, label);
                }
                csv = convertToCSV(csv_data);
                downloadCSV(csv, 'data.csv');

                removed = getSelectedItems('loading', true);
                progressBar.style.display = 'none';

            }

            async function get_post(id) {
                try {
                    const response = await fetch(`/post/${id}`); // Assuming your endpoint is /posts/{id}
                    if (!response.ok) {
                        throw new Error('Failed to fetch post data');
                    }
                    const postData = await response.json();
                    // Process postData as needed
                    console.log('Post data:', postData);
                    return postData;
                } catch (error) {
                    console.error('Error fetching post data:', error);
                    throw error;
                }
            }

            function convertToCSV(data) {
                const headers = [
                    'Tema',
                    'Keyword',
                    'Site',
                    'Categoria',
                    'Ancora 1',
                    'URL do Link 1',
                    'Dofollow_link_1',
                    'Ancora 2',
                    'URL do Link 2',
                    'Dofollow_link_2',
                    'Ancora 3',
                    'URL do Link 3',
                    'Dofollow_link_3',
                    'Imagem',
                    'Insere Imagem no Post',
                    'Link Interno',
                    'Programacao de Postagem',
                    'URL da Publicação',
                    'Nota de SEO',
                    'Dominio',
                    'Gdrive',
                    'Video'
                ];

                const csvRows = [];
                csvRows.push(headers.join(','));

                data.forEach(item => {
                    const row = [
                        item.theme,
                        item.keyword || '',
                        item.site || '', // If 'site' is not available, use an empty string
                        item.category || '', // If 'category' is not available, use an empty string
                        item.anchor_1 || '',
                        item.url_link_1 || '',
                        item.do_follow_link_1 || '',
                        item.anchor_2 || '',
                        item.url_link_2 || '',
                        item.do_follow_link_2 || '',
                        item.anchor_3 || '',
                        item.url_link_3 || '',
                        item.do_follow_link_3 || '',
                        item.post_image || '',
                        item.insert_image || '',
                        item.internal_link || '',
                        item.schedule_date || '',
                        item.post_url || '',
                        item.status || '',
                        item.domain || '',
                        item.gdrive_url || '',
                        item.video || ''
                    ];
                    csvRows.push(row.join(','));
                });

                return csvRows.join('\n');
            }
            function downloadCSV(csvContent, filename) {
                const blob = new Blob([csvContent], { type: 'text/csv' });
                if (window.navigator.msSaveOrOpenBlob) {
                    // For IE
                    window.navigator.msSaveBlob(blob, filename);
                } else {
                    // For other browsers
                    const link = document.createElement('a');
                    if (link.download !== undefined) {
                        const url = URL.createObjectURL(blob);
                        link.setAttribute('href', url);
                        link.setAttribute('download', filename);
                        link.style.visibility = 'hidden';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                }
            }


            //função para gerar documentos em lote
            async function batch_doc(all = false){
                selected_items = getSelectedItems('loading');
                separatedData = separateThemesAndIDs(selected_items);
                const progressBar = document.querySelector('.progress-bar-parent');
                // console.log(separatedData.themes, separatedData.ids);
                // return;
                let errorItems = [];
                if(all)
                {
                    try {
                        await create_gdoc(separatedData.themes, separatedData.ids,separatedData.separatedData.gdrive_document_url);
                    }catch (error) {
                        errorItems.push({ theme: separatedData.themes, id: separatedData.ids });
                    }
                }
                else{
                    let completedItems = 0;
                    const totalItems = Object.keys(separatedData.themes).length;
                    progressBar.style.display = 'block';
                    updateProgressBar(0);
                    for (const theme in separatedData.themes) {
                        const id = separatedData.ids[theme];
                        const drive_url=separatedData.gdrive_document_url[theme]
                        //console.log(drive_url);
                        //const loading_doc=selectedItems[theme]
                        console.log("criando", [theme], [id]);
                        try {
                            await create_gdoc(theme, id, drive_url);
                        } catch (error) {
                            errorItems.push({ theme, id, drive_url });
                        }
                        completedItems++;
                        const progress = Math.round((completedItems / totalItems) * 100);
                        let label = completedItems + "/" + totalItems;
                        updateProgressBar(progress, label);
                    }
                }
                removed = getSelectedItems('loading', true);
                progressBar.style.display = 'none';
                if (errorItems.length > 0) {
                    // If there are errors, display the error messages
                    let errorMessage = "Erros ocorreram enquanto eram gerados os documentos::";
                    errorItems.forEach(item => {
                        errorMessage += `\nTheme: ${item.theme}, ID: ${item.id}`;
                    });

                    Swal.fire({
                        title: 'Um erro ao gerar em lote!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // If no errors, display success message
                    Swal.fire({
                        title: 'Geração em lote concluído!',
                        text: 'Continue?',
                        icon: 'success',
                        confirmButtonText: 'Continue'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // Reload the page
                        }
                    });
                }
            }

            //
            async function batch_generate(all = false){
                selected_items = getSelectedItems('loading');
                separatedData = separateThemesAndIDs(selected_items);
                const progressBar = document.querySelector('.progress-bar-parent');
                // console.log(separatedData.themes, separatedData.ids);
                // return;
                let errorItems = [];
                if(all)
                {
                    try {
                        await generate_post(separatedData.themes, separatedData.ids);
                    }catch (error) {
                        errorItems.push({ theme: separatedData.themes, id: separatedData.ids });
                    }
                }
                else{
                    let completedItems = 0;
                    const totalItems = Object.keys(separatedData.themes).length;
                    progressBar.style.display = 'block';
                    updateProgressBar(0);
                    for (const theme in separatedData.themes) {
                        const id = separatedData.ids[theme];
                        console.log("criando", [theme], [id]);
                        try {
                            await generate_post([theme], [id]);
                        } catch (error) {
                            errorItems.push({ theme, id });
                        }
                        completedItems++;
                        const progress = Math.round((completedItems / totalItems) * 100);
                        let label = completedItems + "/" + totalItems;
                        updateProgressBar(progress, label);
                    }
                }
                removed = getSelectedItems('loading', true);
                progressBar.style.display = 'none';
                if (errorItems.length > 0) {
                    // If there are errors, display the error messages
                    let errorMessage = "Errors occurred while generating posts for:";
                    errorItems.forEach(item => {
                        errorMessage += `\nTheme: ${item.theme}, ID: ${item.id}`;
                    });

                    Swal.fire({
                        title: 'Um erro ao gerar em lote!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // If no errors, display success message
                    Swal.fire({
                        title: 'Geração em lote concluído!',
                        text: 'Continue?',
                        icon: 'success',
                        confirmButtonText: 'Continue'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // Reload the page
                        }
                    });
                }
            }
            function updateProgressBar(progress, label = '') {
                const progressBar = document.querySelector('.progress-bar');
                progressBar.style.width = progress + '%';
                progressBar.setAttribute('aria-valuenow', progress);

                // Update progress label
                const progressLabel = progressBar.querySelector('.progress-label');
                progressLabel.textContent = `${label} ${progress}%`;
            }

            async function batch_post(){
                selected_items = getSelectedItems('loading');
                separatedData = separateThemesAndIDs(selected_items);
                // console.log(separatedData.themes, separatedData.ids);
                // return;
                // Utilizando um loop for assíncrono com async/await para postar em lotes
                for (const id of separatedData.ids) {
                    await post_to_wp(id, true);
                }
                removed = getSelectedItems('loading', true);
                Swal.fire({
                        title: 'Postado em lote com sucesso!',
                        text: 'Continuar?',
                        icon: 'success',
                        confirmButtonText: 'continue'
                    })
            }
            async function batch_delete() {
                try {
                    selected_items = getSelectedItems('loading');
                    separatedData = separateThemesAndIDs(selected_items);

                    for (const id of separatedData.ids) {
                        await delete_post(id);
                    }

                    removed = getSelectedItems('loading', true);

                    // Show success swal
                    swal.fire({
                        title: "Successo!",
                        text: "Os artigos foram deletados!.",
                        icon: "success",
                        buttons: {
                            confirm: {
                                text: "OK",
                                value: true,
                                visible: true,
                                className: "",
                                closeModal: true
                            }
                        }
                    }).then((value) => {
                        if (value) {
                            location.reload(); // Reload the page
                        }
                    });
                } catch (error) {
                    removed = getSelectedItems('loading', true);
                    // Handle errors here
                    console.error("Error occurred:", error);
                    swal.fire({
                        title: "Error!",
                        text: "Um erro aconteceu ao deletar os artigos!.",
                        html: error,
                        icon: "error",
                        button: "OK"
                    });
                }
            }



            async function generate_post(topic_to_generate, id=null, element_status = null, alert=false){
                //element_status.closest('tr').classList.add('loading')
                loading_element(element_status, false);
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
                let data=[]
                let theme=topic_to_generate
                //theme.push(topic_to_generate);
                    let body = {
                        title: theme,
                        _token: csrfToken
                    };
                    if (id !== undefined) {
                        body.id = id;
                    }

                    try {
                        const query = await fetch('/gpt_query', {
                            method: 'POST',
                            body: JSON.stringify(body),
                            headers: { "Content-Type": "application/json" }
                        });



                    // Remove o SVG de loading após a conclusão da query
                    try {
                    if(alert && query.ok){
                        console.log(query);
                    Swal.fire({
                        title: 'Conteúdo criado com sucesso',
                        text: 'Deseja continuar?',
                        icon: 'success',
                        confirmButtonText: 'continue'
                    }).then((result) => {
                        if (result.isConfirmed) {
                                location.reload(); // Reload the page
                            }
                        });
                    }else if(query.ok){
                        console.log(query);
                    //  Swal.fire({
                    //     title: 'Conteúdo criado com sucesso',
                    //     text: 'Deseja continuar?',
                    //     icon: 'success',
                    //     confirmButtonText: 'continue'
                    // }).then((result) => {
                    //     if (result.isConfirmed) {
                    //             location.reload(); // Reload the page
                    //         }
                    //     });

                    }else{
                        console.log('batendo aqui');
                        console.warn(query.error);
                        // throw new Error("Não foi possivel gerar o post: "+query.statusText);
                        setTimeout(async () => {
                            try {
                                await generate_post(topic_to_generate, id, element_status, alert);
                            } catch (error) {
                                console.error('Error generating post:', error);
                            }
                        }, 1000);
                        return;
                    }

                } catch (error) {
                Swal.fire({
                    title: error,
                    text: 'Quer continuar?',
                    icon: 'error',
                    confirmButtonText: 'continue'
                }).then((result) => {
                    if (result.isConfirmed) {
                            location.reload(); // Reload the page
                        }
                    });
                }


                modalDialog.removeChild(loading);
                loading_element(element_status, true);

                // Aqui você pode adicionar código para lidar com a resposta da query, se necessário
            } catch (error) {
                console.error('Ocorreu um erro:', error);

                Swal.fire({
                    title: 'Aconteceu um erro durante o processo',
                    text: error,
                    html: query.error,
                    icon: 'error',
                    confirmButtonText: 'continue'
                }).then((result) => {
                    if (result.isConfirmed) {
                            location.reload(); // Reload the page
                        }
                    });
                // Se ocorrer um erro, é importante remover o SVG de loading para evitar confusão
                //element_status.closest('tr').classList.remove('loading')
                loading_element(element_status, true);
                document.body.removeChild(loadingSVG);
            }
            //element_status.closest('tr').classList.remove('loading')
            loading_element(element_status, true);
        }

            async function post_to_wp(configId, showAlert = true){
                //Essa função não está tentando ajustar o rank do yoast ou arrumar o problema do rank
                const user_id= document.querySelector('.user_id')
                data = {
                        id: configId,
                        user_id: user_id.value,
                        //domain: domain.innerText,
                        _token: csrfToken
                    };

                // console.log(data);
                // return;
                try {
                    const query = await fetch('/post_content', {
                        method: 'POST',
                        body: JSON.stringify(data),
                        headers: { "Content-Type": "application/json" }
                    });

                    if (query.ok) {
                        const data_response = await query.json();
                        // console.log(data_response);

                        //update_yoaust precisa de um request especifico com domain, keyword e id
                        data = {
                            id: configId,
                            user_id: user_id.value,
                            post_id: data_response.post_id,
                            updateYoastRankMath: true,
                            _token: csrfToken
                        };
                        const query_2 = await fetch('/update_yoaust', {
                            method: 'POST',
                            body: JSON.stringify(data),
                            headers: { "Content-Type": "application/json" }
                        });
                    } else {

                        errorText = await query_2.text();
                        console.error(errorText);
                        const titleMatch = errorText.match(/<title>([\s\S]*?)<\/title>/);
                        const errorTitle = titleMatch ? titleMatch[1] : "Error";
                        if (!titleMatch) {
                            errorTitle = errorText;
                        }

                        Swal.fire({
                            title: 'Aconteceu um erro durante o processo',
                            text: query_2.status,
                            html: '<p>'+query_2.status+'</p><pre>' + errorTitle + '</pre>',
                            icon: 'error',
                            confirmButtonText: 'continue'
                        });
                        console.error("Fetch failed with status:", query.status);

                    }
                } catch (error) {
                    console.error("Fetch error:", error);
                }
            }
            function loading_element(el, remove = false){
                if (el instanceof Element) { // Check if el is not null or undefined
                    var parentTr = el.closest('tr');
                    if (remove) {
                        parentTr.classList.remove('loading');
                    } else {
                        parentTr.classList.add('loading');
                    }
                } else {
                    //console.error("Element is null or undefined");
                }
            }

            async function delete_post(data_id){
                const deletion_query= await fetch('/remove_config',{
                method:'DELETE',
                body:JSON.stringify({
                    id:data_id,
                    _token:csrfToken
                }),
                headers:{"Content-Type":"application/json"}
                })
            }
            async function create_gdoc(theme, id, folderLink = '', loading_elements = null) {
                console.log(folderLink);
                if (folderLink == '') {
                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Não tem uma pasta google doc vinculado a esse artigo.'
                    });
                    return;
                }
                if(loading_elements){
                    loading_element(loading_elements, false);
                }
                try {
                    const folderId = folderLink.split('/folders/');
                    const folder = folderId[1];

                    const realFolderId = folder.split('?usp=sharing');
                    console.log(realFolderId[0]);
                    let body = {
                        title: theme,
                        id: id,
                        folder_id: realFolderId[0],
                        _token: csrfToken
                    };

                    const query = await fetch('/create_doc', {
                        method: 'POST',
                        body: JSON.stringify(body),
                        headers: { "Content-Type": "application/json" }
                    });

                    const response = await query.json();

                    console.log(response);
                    loading_element(loading_elements, true);
                    swal.fire({
                        icon: 'success',
                        title: 'Documento criado com sucesso',
                        text: 'O documento do drive fo criado com sucesso'
                    });
                    return response;
                } catch (error) {
                    console.error('Error:', error);
                    // alert
                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Um erro aconteceu ao criar o documento.'
                    });
                }
                loading_element(loading_elements, true);
            }
            async function get_gdoc(title, id, google_docs = '', loading_elements = null){
                if (google_docs === '') {
                    const { value: googleDocsValue } = await swal.fire({
                        title: 'Insira o documento do google drive',
                        input: 'text',
                        inputLabel: 'Google Docs URL',
                        inputPlaceholder: 'Insira a URL Google Docs',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Você precisa inserir um documento do google docs!!';
                            }
                        }
                    });

                    if (googleDocsValue) {
                        google_docs = googleDocsValue;
                    } else {
                        // If user cancels or provides no input, return without proceeding
                        return;
                    }
                }

                if (loading_elements){
                    loading_element(loading_elements, false);
                }

                try {
                    const folderId = google_docs.split('/d/');
                    console.log(folderId);
                    const folder = folderId[1];

                    //const folder_temp = folder.split('?usp=sharing');
                    const folder_2 = folder.split('/edit');
                    const realFolderId  = folder_2[0];

                    console.log(realFolderId[0]);

                    let body = {
                        title: title,
                        id: id,
                        google_docs: realFolderId,
                        _token: csrfToken
                    };

                    const query = await fetch('/process_doc', {
                        method: 'POST',
                        body: JSON.stringify(body),
                        headers: { "Content-Type": "application/json" }
                    });

                    const response = await query.json();

                    console.log(response);
                    if (loading_elements) {
                        loading_element(loading_elements, true);
                    }
                    swal.fire({
                        icon: 'success',
                        title: 'Texto importado com sucesso!',
                        text: 'O texto foi armazenado na base com sucesso'
                    });
                    return response;
                } catch (error) {
                    console.error('Error:', error);
                    // alert
                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Um erro aconteceu durante o processamento: ' +error+ '.'
                    });
                }
                if (loading_elements) {
                    loading_element(loading_elements, true);
                }
            }


            function open_modal(i = 0, data = null) {
                data = data.replace(/[\x00-\x1F\x7F]/g, '');
                let parsedData = JSON.parse(data);
                // console.log(parsedData);

                modal.classList.add('open_editor_modal');

                _editor_id.value = parsedData.editor_id;
                _anchor_1.value = parsedData.anchor_1;
                _anchor_2.value = parsedData.anchor_2;
                _anchor_3.value = parsedData.anchor_3;
                _category.value = parsedData.category;
                _created_at.value = parsedData.created_at;
                _do_follow_link_1.value = parsedData.do_follow_link_1;
                _do_follow_link_2.value = parsedData.do_follow_link_2;
                _do_follow_link_3.value = parsedData.do_follow_link_3;
                _domain.value = parsedData.domain;
                _id.value = parsedData.id;
                _insert_image.value = parsedData.insert_image;
                _internal_link.value = parsedData.internal_link;
                _keyword.value = parsedData.keyword;
                //_post_content.value = parsedData.post_content;
                _post_image.value = parsedData.post_image;
                _schedule_date.value = parsedData.schedule_date;
                _status.value = parsedData.status;
                _theme.value = parsedData.theme;
                _updated_at.value = parsedData.updated_at;
                _url_link_1.value = parsedData.url_link_1;
                _url_link_2.value = parsedData.url_link_2;
                _url_link_3.value = parsedData.url_link_3;
                _video.value = parsedData.video;
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
                  <option value="narrative">Narrative</option>
                  <option value="descriptive">Descriptive</option>
                  <option value="expository">Expository</option>
                  <option value="persuasive">Persuasive</option>
                  <option value="creative">Creative</option>
                  <option value="objective">Objective</option>
                  <option value="subjective">Subjective</option>
                </select>
              </div>
              <div class="form-group">
                <label for="writing_tone">Tom de escrita</label>
                <select name="tone" id="writing_tone">
                  <option value="casual">Casual</option>
                  <option value="eloquent">Eloquent</option>
                  <option value="informal">Informal</option>
                  <option value="optimistic">Optimistic</option>
                  <option value="worried">Worried</option>
                  <option value="friendly">Friendly</option>
                  <option value="curious">Curious</option>
                  <option value="assertive">Assertive</option>
                  <option value="encouraging">Encouraging</option>
                  <option value="surprised">Surprised</option>
                  <option value="neutral">Neutral</option>
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
      const createDocButton= document.querySelector(".create_doc");
      const postContent=document.querySelector('.post-content')
      const languages=document.getElementById("languages");
      const writing_style=document.getElementById("style");
      const writing_tone=document.getElementById('writing_tone');
      const sections=document.getElementById('sections');
      const paragraphs= document.getElementById('paragraphs');
      const user_id= document.querySelector('.user_id')





      document.addEventListener('DOMContentLoaded', function () {
      const generateContentButtons = document.querySelectorAll('.create_content');
      const postButton= document.querySelectorAll(".post_wp");
      let data_id;
      let Theme;

    const container = document.querySelectorAll('.container');

    postButton.forEach((button,i)=>{
        // Encontra o contêiner mais próximo ao botão clicado
        const configId = id[i].getAttribute('data-id');
            // console.log('Config ID:', configId);
        const loading= document.createElement('span');

            loading.innerHTML='loading....'
            button.addEventListener('click',async()=>{
            button.insertAdjacentElement("beforebegin", loading);
            const domain=document.querySelectorAll('.domain')[i].innerText;
            const keyword=document.querySelectorAll('.keyword')[i]
            loading_element(button, false);
            console.log("Postando em: "+domain);
            data = {
                            id: configId,
                            user_id: user_id.value,
                            domain: domain,
                            _token: csrfToken
                        };

                // console.log(data);
                try {
                    const query = await fetch('/post_content', {
                        method: 'POST',
                        body: JSON.stringify(data),
                        headers: { "Content-Type": "application/json" }
                    });

                    if (query.ok) {
                        const data = await query.json();
                        // console.log(data);
                        try {
                            body = {id: configId,
                                    domain: domain,
                                    post_id: data.post_id,
                                    //keyword: keyword.innerText,
                                    updateYoastRankMath: true,
                                    _token: csrfToken};
                            // console.log(body);
                            const query_2 = await fetch('/update_yoaust', {
                                method: 'POST',
                                body: JSON.stringify(body),
                                headers: { "Content-Type": "application/json" }
                            });

                            if (query_2.ok) {
                                const data_2 = await query_2.json();
                                Swal.fire({
                                    title: 'Post criado com sucesso no wordpress',
                                    text: 'Continuar?',
                                    icon: 'success',
                                    confirmButtonText: 'continue'
                                })
                                loading.remove(this);
                                loading_element(button, true);
                                //location.reload();
                            } else {
                                console.error("Second fetch failed with status:", query_2.status);
                                loading.remove(this);
                                loading_element(button, true);
                                Swal.fire({
                                    title: 'Error no processo de criação, verificar validade da chave ou rever conteúdo da configuração',
                                    text: query_2.status,
                                    icon: 'success',
                                    confirmButtonText: 'continue'
                                });
                            }
                        } catch (error_2) {
                            console.error("Second fetch error:", error_2);
                            loading.remove(this);
                            loading_element(button, true);
                            Swal.fire({
                                    title: 'Error no processo de criação, verificar validade da chave ou rever conteúdo da configuração',
                                    text: error_2,
                                    icon: 'success',
                                    confirmButtonText: 'continue'
                                });
                        }
                    } else {
                        loading_element(button, true);

                        console.error("Fetch failed with status:", query.status);
                        console.error(query);
                        //console.error("Error response text:", await query.text());
                        errorText = await query.text();
                        console.error(errorText);
                        const titleMatch = errorText.match(/<title>([\s\S]*?)<\/title>/);
                        let errorTitle = titleMatch ? titleMatch[1] : "Error";
                        if (!titleMatch) {
                            errorTitle = errorText;
                        }
                        console.error(errorTitle);

                        Swal.fire({
                            title: 'Aconteceu um erro durante o processo',
                            text: query.status,
                            html: '<p>'+query.status+'</p><pre>' + errorTitle + '</pre>',
                            icon: 'error',
                            confirmButtonText: 'continue'
                        });
                    }
                } catch (error) {
                    loading_element(button, true);
                    console.error("Fetch error:", error);
                    let errorMessage = '';

                    if (error.response) {
                        // Server responded with a status code out of the range of 2xx
                        errorMessage = await error.response.text();
                    } else if (error.request) {
                        // Request was made but no response was received
                        errorMessage = error.request;
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        errorMessage = error.message;
                    }
                    Swal.fire({
                        title: 'Error no processo de criação, verificar validade da chave ou rever conteúdo da configuração',
                        html: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'continue'
                    });

                }
                loading_element(button, true);
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


    //const id=document.querySelectorAll(".config_id")

    deletebutton.forEach((e,i)=>{
    e.addEventListener('click',async ()=>{
        data_id=id[i].getAttribute("data-id");
        // console.log(data_id);
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
                text: 'Continuar?',
                icon: 'success',
                confirmButtonText: 'continue'
            }).then((result) => {
                    if (result.isConfirmed) {
                            location.reload(); // Reload the page
                        }
                    });
        }else{
        Swal.fire({
                title: 'Erro ao remover configuração',
                text: 'Continuar?',
                icon: 'error',
                confirmButtonText: 'continue'
            }).then((result) => {
                    if (result.isConfirmed) {
                            location.reload(); // Reload the page
                        }
                    });

            //location.reload();
        }
    })
    })

</script>


@endsection
