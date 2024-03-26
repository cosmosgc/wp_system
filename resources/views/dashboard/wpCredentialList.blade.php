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
        .editor_list_flex{
            display: flex;
            flex-direction:row;
        }
        </style>

        <div class="dashboard-content">
        <h1>Lista credenciais wordpress</h1>

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

                    @foreach($editor as $edit)
                        @foreach($edit->links as $links)
                        <input type="hidden" name="user_id" class="id" value="{{$edit->id}}">

                        <tr>
                            <td class="editor_name">{{$edit->name}}</td>
                            <td class="wp_login">{{$links->wp_login}}</td>
                            <td class="wp_password">{{$links->wp_password}}</td>
                            <td class="wp_domain">{{$links->wp_domain}}</td>
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
        @endsection
