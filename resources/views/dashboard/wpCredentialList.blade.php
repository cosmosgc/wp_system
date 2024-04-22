        @extends('layouts.app')

        @section('content')

        @php
            $searchParam = request()->input('query');
        @endphp
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



        .upgrade_button {
            border-radius: 20px; /* Ajuste o valor conforme desejado */
            width: 25%;

        }

        .close_modal_button{
            position: absolute;
            top: 15px;
            right: 17px;
        }
        .editor_list_flex{
            display: flex;
            flex-direction:row;
        }
        .open_editor_modal{
            position: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        @media(min-width:700px){
            .open_editor_modal{
                left: 530px;
                max-width: 500px;
            }
        }
        </style>

        <div class="dashboard-content">
        <h1>Lista credenciais wordpress</h1>

        <div class="card card-medium">
            <div class="card-body">
              <div class="search_bar">
                <form action="/list_credential" method="get">
                  <div class="input-group">
                    <input type="text" class="form-control" name="query" id="query" placeholder="Buscar por Nome do post ou Dominio" value="{{$searchParam}}">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Editor</th>
                        <th>Login do wordpress</th>
                        <th>Senha do wordpress</th>
                        <th>Dominío</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>

                    @if(empty($search))

                    @foreach($editor as $edit)
                        @foreach($edit->links as $links)
                        <input type="hidden" name="user_id" class="id" value="{{$links->id}}">

                        <tr>
                            <td class="editor_name">{{$edit->name}}</td>
                            <td class="wp_login">{{$links->wp_login}}</td>
                            <td class="wp_password">{{$links->wp_password}}</td>
                            <td  class="wp_domain">{{$links->wp_domain}}</td>
                            <td class="editor_list_flex">
                                <form action="{{route('credentialDelete',$links->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit"><i class="fas fa-trash">Excluir</i></button>
                                </form>

                                <button class="update btn btn-success"><i class="fas fa-sync-alt">Alterar</i></button>
                            </td>
                        </tr>


                        @endforeach

                    @endforeach

                    @else
                    @foreach($editor as $edit)
                    @foreach($search as $links)
                    <input type="hidden" name="user_id" class="id" value="{{$links->id}}">

                    <tr>
                        <td class="editor_name">{{$edit->name}}</td>
                        <td class="wp_login">{{$links->wp_login}}</td>
                        <td class="wp_password">{{$links->wp_password}}</td>
                        <td  class="wp_domain">{{$links->wp_domain}}</td>
                        <td class="editor_list_flex">
                            <form action="{{route('credentialDelete',$links->id)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash">Excluir</i></button>
                            </form>

                            <button class="update btn btn-success"><i class="fas fa-sync-alt">Alterar</i></button>
                        </td>
                    </tr>


                    @endforeach

                @endforeach
                @endif

                </tbody>
            </table>
        </div>

        <div class="editor_modal">
            <div class="editor_modal_content">
              <input type="text" name="login" id="login" class="form-control" placeholder="Login">
              <input type="text" name="pass" id="pass" class="form-control" placeholder="Senha">
              <input type="text" name="domain" id="domain" class="form-control" placeholder="Dominío">
              <input type="hidden" name="id" id="id">
            </div>
            <button class="btn btn-primary upgrade_button">Atualizar</button>
            <button class="btn btn-danger close_modal_button">X</button>
          </div>


        <script>
          const login = document.querySelectorAll(".wp_login");
          const pass = document.querySelectorAll(".wp_password");
          const domain = document.querySelectorAll(".wp_domain");
          const id=document.querySelectorAll(".id");
          const loginModal= document.getElementById("login");
          const passwordModal=document.getElementById("pass");
          const domainModal=document.getElementById("domain");
          const modal= document.querySelector(".editor_modal");
          const closeModalButton=document.querySelector(".close_modal_button");
          const upgradeButton=document.querySelector(".upgrade_button");
          var idModal= document.getElementById("id");

          const update=document.querySelectorAll('.update');
          const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

          update.forEach((e,i)=>{
            e.addEventListener('click',()=>{
                modal.classList.add('open_editor_modal');
                loginModal.value=login[i].innerText;
                passwordModal.value=pass[i].innerText;
                domainModal.value=domain[i].innerText;
                idModal=id[i].value;

            })
        })

        upgradeButton.addEventListener('click',async ()=>{
            const update_query= await fetch('/update_credentials',{
                method:'PUT',
                body:JSON.stringify({
                    id:idModal,
                    login:loginModal.value,
                    password:passwordModal.value,
                    domain:domainModal.value,
                    _token:csrfToken

                }),

                headers:{
                    "Content-Type":"application/json"
                }
            })

            if(update_query.ok){
                alert('Novas credenciais salvas com sucesso');
                location.reload();
            }else{
                alert('Erro ao salvar credenciais');
                location.reload();
            }
        })

        closeModalButton.addEventListener('click',()=>{
            modal.classList.remove('open_editor_modal');
        })


        </script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all elements with class 'wp_domain'
        var domains = document.querySelectorAll('.wp_domain');

        domains.forEach(function(domain) {
            // Get the domain value
            var domainValue = domain.textContent.trim();
            if (!domainValue.startsWith('http://') && !domainValue.startsWith('https://')) {
                // Prepend 'https://' if it doesn't already start with http:// or https://
                domainValue = 'https://' + domainValue;
            }
            // Perform fetch request to ping the domain
            fetch(domainValue+"/wp-json/wp/v2/posts", {
                method: 'GET',
                //mode: 'cors', // Ensure CORS mode
                redirect: 'follow',
                headers: {
                    'Content-Type': 'text/plain'
                }
            })
            .then(function(response) {
                if (response.ok) {
                    // Domain is reachable, add class 'ping_true'
                    domain.classList.add('ping_true');
                } else {
                    // Domain is unreachable, add class 'ping_false'
                    domain.classList.add('ping_false');
                }
            })
            .catch(function(error) {
                // Domain is unreachable, add class 'ping_false'
                domain.classList.add('ping_false');
            });
        });
    });
</script>

<!-- Add this CSS code to your <style> tag or external CSS file -->
<style>
    .ping_true {
        color: green;
    }

    .ping_false {
        color: red;
    }
    .ping_true::after {
        content: ' ✓ Funciona'; /* Add a checkmark symbol after ping_true domains */
        color: green;
    }

    .ping_false::after {
        content: ' ✗ Erro'; /* Add a cross symbol after ping_false domains */
        color: red;
    }
    .ping_true::after,
    .ping_false::after {
        display: inline-block;
        padding: 3px 6px;
        margin-left: 5px;
        border: 1px solid;
        border-radius: 3px;
    }
</style>

        @endsection
