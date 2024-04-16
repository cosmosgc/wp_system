@extends('layouts.app')

@section('content')
@php
    use App\Models\Editor;
    use Illuminate\Http\Request;


    $valorCodificado = request()->cookie('editor');
    $user=explode('+',base64_decode($valorCodificado));
    $test=Editor::where('name',$user[0])->get();

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

    .open_editor_modal{
        position: fixed;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    @media(max-width:600px){
        .open_editor_modal{
            left: 500px;
            max-width: 400px;
        }
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

<h2>Lista de editores</h2>
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
                        <td class="editor_list_flex">
                        <form action="{{ route('editor.destroy', $editor->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir">
                                <i class="fas fa-trash-alt"></i> <span class="visually-hidden">Excluir</span>
                            </button>
                        </form>

                        <button class="update btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Alterar">
                            <i class="fas fa-edit"></i> <span class="visually-hidden">Alterar</span>
                        </button>

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
            @if($test[0]->is_admin==1)
                <input type="checkbox" name="admin" id="admin" class="form-check-input">
                <label for="admin">is admin?</label>
            @endif
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
