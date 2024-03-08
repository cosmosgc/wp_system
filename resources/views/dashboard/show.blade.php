<!-- resources/views/dashboard/show.blade.php -->
@extends('layouts.app')
@php  
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    $valorCodificado = request()->cookie('editor');
    $user=explode('+',base64_decode($valorCodificado));
    $editors = DB::table('editors')->get();
@endphp

@section('content')
    <style>
        .editor_modal{
            height: 100vh;
            width: 100%;
            position: absolute;
            top: 0;
            background: rgba(0,0,0,.6);
            display: none;
            color: #fff;

        }

        .editor_modal_content{
            width:50%
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
            right: 316px;
        }

    </style>
    <div class="dashboard-content">
        <h1>Bem-vindo {{$user[0]}} </h1>
        <!-- Conteúdo dinâmico -->
        <!-- Display the table -->
        <br>
        <h2>Usuários cadastrados</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sobrenome</th>
                    <th>CPF</th>
                    <th>CNPJ</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($editors as $editor)
                    <tr>
                        <td class="id">{{ $editor->id }}</td>
                        <td class="name">{{ $editor->name }}</td>
                        <td class="surname">{{ $editor->surname }}</td>
                        <td class="cpf">{{ $editor->cpf }}</td>
                        <td class="cnpj">{{ $editor->cnpj }}</td>
                        <td class="email">{{ $editor->email }}</td>
                        <td>
                            <form action="{{ route('editor.destroy', $editor->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Excluir</button>
                            </form>

                            <button class="update">Alterar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="editor_modal">
        <div class="editor_modal_content">
          <input type="text" name="name" id="name" class="form-control" placeholder="Nome">
          <input type="text" name="surname" id="surname" class="form-control" placeholder="Sobrenome">
          <input type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF">
          <input type="text" name="cnpj" id="cnpj" class="form-control" placeholder="CNPJ">
          <input type="email" name="email" id="email" class="form-control" placeholder="Email">
          <input type="password" name="password" id="pass" class="form-control" placeholder="Senha">
          <input type="checkbox" name="admin" id="admin" class="form-check-input">
          <label for="admin">is admin?</label>
          <input type="hidden" name="id" id="id">
        </div>
        <button class="btn btn-primary upgrade_button">Atualizar</button>
        <button class="btn btn-danger close_modal_button">X</button>
      </div>

    <script>
        const id =document.querySelectorAll('.id')
        const name =document.querySelectorAll('.name')
        const surname =document.querySelectorAll('.surname');
        const cpf =document.querySelectorAll('.cpf')
        const cnpj =document.querySelectorAll('.cnpj')
        const email =document.querySelectorAll('.email')
        const modal= document.querySelector(".editor_modal");
        const closeModalButton=document.querySelector(".close_modal_button");
        const upgradeButton=document.querySelector(".upgrade_button")
        const modalName= document.getElementById("name");
        const modalSurname= document.getElementById("surname");
        const modalCpf= document.getElementById("cpf");
        const modalCnpj= document.getElementById("cnpj");
        const modalEmail= document.getElementById("email");
        const modalPassword=document.getElementById("pass");
        const modalId=document.getElementById("id");
        const modalAdmin=document.getElementById("admin");
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
        

        const update=document.querySelectorAll('.update');

        update.forEach((e,i)=>{
            e.addEventListener('click',()=>{
                modal.classList.add('open_editor_modal');
                modalName.value=name[i].innerText;
                modalSurname.value=surname[i].innerText;
                modalCpf.value=cpf[i].innerText;
                modalCnpj.value=cnpj[i].innerText;
                modalEmail.value=email[i].innerText;
                modalId.value=id[i].innerText;
            })
        })

        upgradeButton.addEventListener('click',async ()=>{
            const updateQuery= await fetch('/update_user',{
                method:'PUT',
                body:JSON.stringify({
                    id:modalId.value,
                    name:modalName.value,
                    surname:modalSurname.value,
                    cpf:modalCpf.value,
                    cnpj:modalCnpj.value,
                    email:modalEmail.value,
                    password:modalPassword.value,
                    is_admin:modalAdmin.checked,
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

    </script>
@endsection
