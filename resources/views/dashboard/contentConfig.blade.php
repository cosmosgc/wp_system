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
<!-- <div class="flex-container-column">
    <button id="adddocument" class="btn btn-primary">Adicionar documento</button>
    <button id="removedocument" class="btn btn-danger">Limpar documendos</button>
</div> -->
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
            <td>Site</td>
            <td>
            <select class="domain">
                @foreach($credentials as $credential)
                    <option value="{{$credential->wp_domain}}">{{$credential->wp_domain}}</option>

                @endforeach
            </select>
            </td>
        </tr>
        <tr>
            <td>Agendar</td>
            <td class="schedule_date" contenteditable="true"><input class="schedule" type="datetime-local"></td>
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
            <td>Imagem URL</td>
            <td class="url_image" contenteditable="true"></td>
        </tr>
        <tr>
            <td>Pasta GoogleDrive URL</td>
            <td class="gdrive_document_url" contenteditable="true"></td>
        </tr>
        <tr>
            <td>Imagem GoogleDrive URL</td>
            <td class="gdrive_url" contenteditable="true"></td>
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
            <td>Follow Link 1</td>
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
            <td>Follow Link 2</td>
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
            <td>Follow Link 3</td>
            <td><input type="checkbox" class="do_follow_link_3" name="" id=""></td>
        </tr>
        <tr>
            <td>Inserir video</td>
            <td><input type="checkbox" class="video" name="" id=""></td>
        </tr>







        </table>
        <input type="hidden" name="user" class="user" value="{{$user[0]}}">
        <button type="button" class="btn btn-outline-primary submitForm">Salvar config</button>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {


            ///carregar categorias do domino
            var domain=document.querySelectorAll('.domain')
            var category= document.querySelectorAll('.category')
            domain.forEach(async (e,i)=>{
                if(!e.value==""){
                await dynamicCategories(e.value,i);
            }

            e.addEventListener('change',async (j)=>{
                        await dynamicCategories(e.value,i);
                    })
            })




            async function dynamicCategories(domain,index){


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
                        let categories= await getSiteCategories(domain);
                        categories.forEach((e)=>{
                        const option=document.createElement('option');
                        option.value=e.name;
                        option.innerText=e.name;
                        category[index].appendChild(option);
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
                    const str=document.querySelector('.gdrive_document_url').innerText;
                    let folderId=null;
                    if(str){

                        folderId=str.split('/folders/')[1]
                    }
                    folderId=document.querySelector('.gdrive_document_url').innerText;

                    console.log(folderId);


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
                        gdrive_document_url: folderId,
                        insert_image: document.querySelector('.insert_image input[type="checkbox"]').checked ? 1 : 0,
                        schedule: document.querySelector('.schedule').value,
                        domain: document.querySelector('.domain').value,
                        video: document.querySelector('.video').checked?1:0,
                        session_user: document.querySelector('.user').value
                    };
                    const loading=document.createElement('span');
                    loading.classList.add('loading')
                    loading.innerText='loading....'
                    const content=document.querySelector(".content");
                    // // Faz a requisição AJAX
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
                            text: 'Voce quer continuar?',
                            icon: 'success',
                            confirmButtonText: 'continue'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                //window.location.reload();
                            }
                        });

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
            var domain=document.querySelectorAll('.domain')
            var category= document.querySelectorAll('.category')
            domain.forEach(async (e,i)=>{
                if(!e.value==""){
                await dynamicCategories(e.value,i);
            }

            e.addEventListener('change',async (j)=>{
                        await dynamicCategories(e.value,i);
                    })
            })

            async function dynamicCategories(domain,index){


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
                let categories= await getSiteCategories(domain);
                categories.forEach((e)=>{
                const option=document.createElement('option');
                option.value=e.name;
                option.innerText=e.name;
                category[index].appendChild(option);
                })
            } catch (error) {
                console.error(error);
            }
            }

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
                        <td>Follow Link 1 1</td>
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
                        <td>Follow Link  2</td>
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
                        <td>Follow Link  3</td>
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
                        <td>ID da pasta do drive</td>
                        <td class="image_folder_id" contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td>Imagem de destaque</td>
                        <td class="insert_image"><input type="checkbox"></td>
                    </tr>
                    <tr>
                        <td>Post Image</td>
                        <td class="sys_image_custom"><input type="file"></td>
                    </tr>
                    <tr>
                        <td>Agendar</td>
                        <td class="schedule_date" contenteditable="true"><input type="date"></td>
                    </tr>
                    <tr>
                        <td>Site</td>
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
            const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
            const str=inputElements.querySelector('.gdrive_document_url').innerText;
            const folderId=null;
            if(str.length){
                folderId=str[1]
            }
            folderId=inputElements.querySelector('.gdrive_document_url').innerText;

            var postData = {
                        theme: inputElements.querySelector('.theme').innerText,
                        keyword: inputElements.querySelector('.keyword').innerText,
                        category: inputElements.querySelector('.category').value,
                        anchor_1: inputElements.querySelector('.anchor_1').innerText,
                        url_link_2: inputElements.querySelector('.url_link_2').innerText,
                        do_follow_link_1: inputElements.querySelector('.do_follow_link_1').checked ? 1 : 0,
                        anchor_2: inputElements.querySelector('.anchor_2').innerText,
                        do_follow_link_2: inputElements.querySelector('.do_follow_link_2').checked ? 1 : 0,
                        anchor_3: inputElements.querySelector('.anchor_3').innerText,
                        url_link_3: inputElements.querySelector('.url_link_3').innerText,
                        do_follow_link_3: inputElements.querySelector('.do_follow_link_3').checked ? 1 : 0,
                        image_url: inputElements.querySelector('.url_image').innerText,
                        gdrive_document_url:folderId,
                        insert_image: inputElements.querySelector('.insert_image input[type="checkbox"]').checked ? 1 : 0,
                        schedule_date: inputElements.querySelector('.schedule_date input[type="date"]').value,
                        domain: inputElements.querySelector('.domain').value,
                        session_user: document.querySelector('.user').value
                    };
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
                            text: 'Voce quer continuar?',
                            icon: 'success',
                            confirmButtonText: 'continue'
                        })
                    }})
                    loading.innerText='';

        }
</script>
@endsection
