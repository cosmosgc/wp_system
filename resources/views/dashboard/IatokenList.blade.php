@extends('layouts.app')

@section('content')

<style>
    .credential_token_modal {
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
    @media(min-width:700px){
            .open_editor_modal{
                left: 530px;
                max-width: 500px;
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

<div class="dashboard-content">
    <br>
    <h2>Tokens da Open-ia Cadastrados</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nome do Editor</th>
                <th>token</th>
                <th>Ações</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($editor as $edit)
                @if(isset($edit->iaCredentials))
                    <tr>
                        <td class="editor_name">{{ $edit->name }}</td>
                        <td class="token">{{isset($edit->iaCredentials->open_ai)?$edit->iaCredentials->open_ai:null}}</td>
                        <td class="editor_list_flex">
                            <form action="{{route('deleteToken',isset($edit->iaCredentials->id)?$edit->iaCredentials->id:0)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash">Excluir</i></button>
                            </form>

                            <button class="update btn btn-success"><i class="fas fa-sync-alt">Alterar</i></button>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>


<div class="credential_token_modal">
    <div class="editor_modal_content">
    <input type="hidden" name="editor" id="editor">
      <input type="text" name="token" id="token" class="form-control" placeholder="Token">
      <input type="hidden" name="id" id="id">
    </div>
    <button class="btn btn-primary upgrade_button">Atualizar</button>
    <button class="btn btn-danger close_modal_button">X</button>
</div>

<script>
    const editor_name =document.querySelectorAll('.editor_name')
    const ia_token =document.querySelectorAll('.token')
    const modal= document.querySelector(".credential_token_modal");
    const closeModalButton=document.querySelector(".close_modal_button");
    const upgradeButton=document.querySelector(".upgrade_button")
    const modalName= document.getElementById("editor");
    const modalToken=document.getElementById("token");
    const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;


    const update=document.querySelectorAll('.update');

    update.forEach((e,i)=>{
        e.addEventListener('click',()=>{
            modal.classList.add('open_editor_modal');
            modalToken.value=ia_token[i].textContent;
            modalName.value=editor_name[i].textContent;
;
        })
    })

    upgradeButton.addEventListener('click',async ()=>{
        const updateQuery= await fetch('/submit_ia_token',{
            method:'POST',
            body:JSON.stringify({
                editor:modalName.value,
                token:modalToken.value,
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
