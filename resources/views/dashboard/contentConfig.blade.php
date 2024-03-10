<!-- resources/views/create_post_content.blade.php -->

@extends('layouts.app') <!-- Assuming you have a layout file, adjust accordingly -->
@php
use App\Models\Editor;
use App\Models\Wp_credential;
use Illuminate\Http\Request;
$valorCodificado = request()->cookie('editor');
$credentials=Wp_credential::all();


$user=explode('+',base64_decode($valorCodificado));  

@endphp

@section('content')
<h3>Criar configuração para postagem</h3>
<div class="flex-container-column">
    <button id="adddocument" class="btn btn-primary">Adicionar documento</button>
    <button id="removedocument" class="btn btn-danger">Limpar documendos</button>
</div>
@foreach($credentials as $credential)
    <input type="hidden" name="opt" class="domain_options" value="{{$credential->wp_domain}}">

@endforeach


    <div class="container editable-document">
        <table>
        <tr>
            <th>Label</th>
            <th>Input</th>
        </tr>
        <tr>
            <td>Tema</td>
            <td class="theme" contenteditable="true"></td>
        </tr>
        <tr>
            <td>Palavra chave</td>
            <td class="keyword" contenteditable="true"></td>
        </tr>
        <tr>
            <td>Categoria</td>
            <td><select class="category" name="" id=""></select></td>
        </tr>
        <tr>
            <td>Ancora 1</td>
            <td class="anchor_1" contenteditable="true"></td>
        </tr>
        <tr>
            <td>URL Link 1</td>
            <td class="url_link_1" contenteditable="true"></td>
        </tr>
        <tr>
            <td>Seguir Link 1</td>
            <td><input type="checkbox" class="do_follow_link_1" name="" id=""></td>
        </tr>
        <tr>
            <td>Ancora 2</td>
            <td class="anchor_2" contenteditable="true"></td>
        </tr>
        <tr>
            <td>URL Link 2</td>
            <td class="url_link_2" contenteditable="true"></td>
        </tr>
        <tr>
            <td>Seguir Link 2</td>
            <td><input type="checkbox" class="do_follow_link_2" name="" id=""></td>
        </tr>
        <tr>
            <td>Ancora 3</td>
            <td class="anchor_3" contenteditable="true"></td>
        </tr>
        <tr>
            <td>URL Link 3</td>
            <td class="url_link_3" contenteditable="true"></td>
        </tr>
        <tr>
            <td>Seguir Link 3</td>
            <td><input type="checkbox" class="do_follow_link_3" name="" id=""></td>
        </tr>
        <tr>
            <td>Imagem URL</td>
            <td class="url_image" contenteditable="true"></td>
        </tr>
        <tr>
            <td>GoogleDrive URL</td>
            <td class="gdrive_url" contenteditable="true"></td>
        </tr>
        <tr>
            <td>ID da pasta do drive</td>
            <td class="image_folder_id" contenteditable="true"></td>
        </tr>
        <tr>
            <td>Imagem de destaque</td>
            <td class="insert_image"><input type="checkbox"></td>
        </tr>
        <tr>
            <td>Imagem do post</td>
            <td class="sys_image"><input type="file"></td>
        </tr>
        <tr>
            <td>Agendar</td>
            <td class="schedule_date" contenteditable="true"><input class="schedule" type="date"></td>
        </tr>
        <tr>
            <td>Site</td>
            <td>
            <select class="domain">
                @foreach($credentials as $credential)
                    <option value="{{$credential->wp_domain}}">{{$credential->wp_domain}}</option>

                @endforeach
            </select>
            </td>
        </tr>
        </table>
        <input type="hidden" name="user" class="user" value="{{$user[0]}}">
        <button type="button" class="btn btn-outline-primary submitForm">Salvar config</button>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {

            
            ///carregar categorias do domino
            var domain=document.querySelector('.domain')
            var category= document.querySelector('.category')
            if(!domain.value==null){
                await dynamicCategories();
            }
            
            domain.addEventListener('change',async (e)=>{
                        await dynamicCategories();
                    })
            
            async function dynamicCategories(){

                                    
                    async function getSiteCategories(domain){
                        try {
                            const regex = /^(https?:\/\/)/i;
                            let new_domain=domain.replace(regex,'');
                            console.log(new_domain);
                            const domain_query= await fetch(`https://${new_domain}/wp-json/wp/v2/categories`);  
                            const response =await domain_query.json();
                            return response; 
                            
                        } catch (error) {
                            Swal.fire({
                            title: error,
                            text: 'Do you want to continue',
                            icon: 'error',
                            confirmButtonText: 'continue'
                        })
                        }
   
                     }

                     try {
                        let categories= await getSiteCategories(domain.value);
                        categories.forEach((e)=>{
                        const option=document.createElement('option');
                        option.value=e.name;
                        option.innerText=e.name;
                        category.appendChild(option);
                        })
                     } catch (error) {
                        console.error(error);
                     }






            }




            ////////////////////

            var submitButtons = document.querySelectorAll('.submitForm');
            const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

            submitButtons.forEach(function(submitButton) {
                submitButton.addEventListener('click', function(event) {
                    event.preventDefault(); // Evita o comportamento padrão de envio do formulário
        
                    // Encontra o "document" pai do botão clicado
        
                    // Coleta dos dados do formulário dentro do "document" atual
                    var formData = new FormData();
                    var imageFile = document.querySelector('.sys_image input[type="file"]').files[0];

                    if (imageFile) {
                        formData.append('sys_image', imageFile);
                    }

                    var postData = {
                        theme: document.querySelector('.theme').innerText,
                        keyword: document.querySelector('.keyword').innerText,
                        category: document.querySelector('.category').value,
                        anchor_1: document.querySelector('.anchor_1').innerText,
                        url_link_2: document.querySelector('.url_link_2').innerText,
                        do_follow_link_1: document.querySelector('.do_follow_link_1').checked ? 1 : 0,
                        anchor_2: document.querySelector('.anchor_2').innerText,
                        do_follow_link_2: document.querySelector('.do_follow_link_2').checked ? 1 : 0,
                        anchor_3: document.querySelector('.anchor_3').innerText,
                        url_link_3: document.querySelector('.url_link_3').innerText,
                        do_follow_link_3: document.querySelector('.do_follow_link_3').checked ? 1 : 0,
                        image_url: document.querySelector('.url_image').innerText,
                        gdrive_url: document.querySelector('.gdrive_url').innerText,
                        folder_id: document.querySelector('.image_folder_id').innerText,
                        insert_image: document.querySelector('.insert_image input[type="checkbox"]').checked ? 1 : 0,
                        schedule: document.querySelector('.schedule').value,
                        domain: document.querySelector('.domain').value,
                        session_user: document.querySelector('.user').value
                    };



                    console.log(postData);
                    const loading=document.createElement('span');
                    loading.classList.add('loading')
                    loading.innerText='loading....'
                    const content=document.querySelector(".content");
                    // Faz a requisição AJAX
                    content.appendChild(loading);
                    fetch('/insert_post_content', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(postData),
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(errorMessage => {
                                console.log(errorMessage);
                                if (errorMessage) {
                                    alert(errorMessage);
                                } else {
                                    // Se a resposta estiver truncada ou vazia, mostra uma mensagem genérica de erro
                                    Swal.fire({
                                        title: 'Erro',
                                        text: 'A resposta foi truncada ou está vazia. Por favor, tente novamente.',
                                        icon: 'error',
                                        confirmButtonText: 'continue'
                                    });
                                }
                            });
                        }else{
                            Swal.fire({
                            title: 'Configuração salva com sucesso',
                            text: 'DVoce quer continuar?',
                            icon: 'success',
                            confirmButtonText: 'continue'
                        })

                        loading.innerText='';

                        }
                        
                    })
                                        

                });
            });
        });
        </script>

<script>
    var adddocumentButton = document.getElementById('adddocument');
        var removedocumentButton = document.getElementById('removedocument');
        const domain= document.querySelectorAll(".domain_options")
    
        adddocumentButton.addEventListener('click', function(event) {
            event.preventDefault();
    
            var newdocument = createNewdocument();
            document.body.appendChild(newdocument);
            bindSubmitEvent(newdocument);
        });
    
        removedocumentButton.addEventListener('click', function(event) {
            event.preventDefault();
    
            var selecteddocument = document.querySelector('.editable-document.selected');
            if (selecteddocument) {
                selecteddocument.remove();
            } else {
                var alldocuments = document.querySelectorAll('.editable-document');
                alldocuments.forEach(function(document) {
                    document.remove();
                });
            }
        });

        function bindSubmitEvent(documentElement) {
            var submitButton = documentElement.querySelectorAll('.submitForm');
            submitButton.forEach((e,index)=>{
                submitButton[index].addEventListener('click', function(event) {
                event.preventDefault();
                console.log(document.querySelectorAll('.theme'));

            })


                // Coleta dos dados do formulário dentro do elemento do documento

                
            });
        }
    
        function createNewdocument(id = 1) {
            var newdocument = document.createElement('div');
            newdocument.classList.add('content');
            newdocument.classList.add('editable-document');
            newdocument.id = id;
            newdocument.innerHTML = `
            <div class="container">
                    <table>
                    <tr>
                        <th>Label</th>
                        <th>Input</th>
                    </tr>
                    <tr>
                        <td>Theme</td>
                        <td class="theme" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>Keyword</td>
                        <td class="keyword" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td class="category" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>Anchor 1</td>
                        <td class="anchor_1" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>URL Link 1</td>
                        <td class="url_link_1" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>Do Follow Link 1</td>
                        <td><input type="checkbox" class="do_follow_link_1" name="" id=""></td>
                    </tr>
                    <tr>
                        <td>Anchor 2</td>
                        <td class="anchor_2" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>URL Link 2</td>
                        <td class="url_link_2" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>Do Follow Link 2</td>
                        <td><input type="checkbox" class="do_follow_link_2" name="" id=""></td>
                    </tr>
                    <tr>
                        <td>Anchor 3</td>
                        <td class="anchor_3" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>URL Link 3</td>
                        <td class="url_link_3" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>Do Follow Link 3</td>
                        <td><input type="checkbox" class="do_follow_link_3" name="" id=""></td>
                    </tr>
                    <tr>
                        <td>Image URL</td>
                        <td class="url_image" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>GoogleDrive URL</td>
                        <td class="gdrive_url" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>Folder ID</td>
                        <td class="image_folder_id" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>Use Featured image</td>
                        <td class="insert_image"><input type="checkbox"></td>
                    </tr>
                    <tr>
                        <td>Post Image</td>
                        <td class="sys_image_custom"><input type="file"></td>
                    </tr>
                    <tr>
                        <td>Schedule</td>
                        <td class="schedule_date" contenteditable="true"><input type="date"></td>
                    </tr>
                    <tr>
                        <td>Domain</td>
                        <td>
                        <select class="domain">
                            ${Object.keys(domain).map(key => `<option value="${domain[key].value}">${domain[key].value}</option>`).join('')}
                        </select>
                        </td>
                    </tr>
                    </table>
                    <button type="button" class="btn btn-outline-primary submitForm">Salvar config</button>

                </div>
            `;
            var submitButton = newdocument.querySelector('.submitForm');

            submitButton.addEventListener('click', function() {
                getDataFromTable(newdocument);
            });
            return newdocument;
        }

        function getDataFromTable(element) {
            var inputElements = element;

            var postData = {
                        theme: inputElements.querySelector('.theme').innerText,
                        keyword: inputElements.querySelector('.keyword').innerText,
                        category: inputElements.querySelector('.category').innerText,
                        anchor_1: inputElements.querySelector('.anchor_1').innerText,
                        url_link_2: inputElements.querySelector('.url_link_2').innerText,
                        do_follow_link_1: inputElements.querySelector('.do_follow_link_1').checked ? 1 : 0,
                        anchor_2: inputElements.querySelector('.anchor_2').innerText,
                        do_follow_link_2: inputElements.querySelector('.do_follow_link_2').checked ? 1 : 0,
                        anchor_3: inputElements.querySelector('.anchor_3').innerText,
                        url_link_3: inputElements.querySelector('.url_link_3').innerText,
                        do_follow_link_3: inputElements.querySelector('.do_follow_link_3').checked ? 1 : 0,
                        image_url: inputElements.querySelector('.url_image').innerText,
                        gdrive_url: inputElements.querySelector('.gdrive_url').innerText,
                        folder_id: inputElements.querySelector('.image_folder_id').innerText,
                        insert_image: inputElements.querySelector('.insert_image input[type="checkbox"]').checked ? 1 : 0,
                        schedule_date: inputElements.querySelector('.schedule_date input[type="date"]').value,
                        domain: inputElements.querySelector('.domain').value,
                        //session_user: inputElements.querySelector('.user').value
                    };

                    console.log(postData);
                    fetch('/insert_post_content', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(postData),
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (!response.ok) {
                            Swal.fire({
                            title: 'Erro ao salvar configuração',
                            text: 'Do you want to continue',
                            icon: 'error',
                            confirmButtonText: 'continue'
                        })
                        }else{
                            Swal.fire({
                            title: 'Configuração salva com sucesso',
                            text: 'Do you want to continue',
                            icon: 'success',
                            confirmButtonText: 'continue'
                        })
                        }
                    }})
                
        }
</script>
@endsection
