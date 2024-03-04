<!-- resources/views/layouts/app.blade.php -->
@php  
    use App\Models\Editor;
    use Illuminate\Http\Request;
    

    $valorCodificado = request()->cookie('Editor');
    $user=explode('+',base64_decode($valorCodificado));
    $test=Editor::where('name',$user[0])->get();

@endphp

@if(!empty($valorCodificado))
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        /* public/css/styles.css */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #86C995; /* Verde leve */
            padding-top: 20px;
            transition:.8s

        }

        .sidebar h2 {
            color: white;
            text-align: center;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar li {
            padding: 8px;
            text-align: center;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 8px;
        }

        .sidebar a:hover {
            background-color: #5EAD78; /* Verde mais escuro ao passar o mouse */
            transition: .5s;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .sidebar i {
            margin-right: 8px;
    
        }

        li a{
            display: flex;
            justify-content: space-between;
        }

        ul{
            width: 100%;
        }

        .open_side{
            margin-left: 237px;
            display: none;
            border-radius: 30px;
            background: #fff;
            border: 2px solid #ddd
        }

        .close_side{
            margin-left: 213px;
            border-radius: 30px;
            width: 30px;
            height: 30px;
            background: transparent;
            color: #fff;
            border: 2px #fff solid;
        }

        @media(max-width:600px){
            .content{
                margin-left: 20px;
            }

            .sidebar{
                margin-left: -234px
            }

            .open_side{
                display: block;
            }

            .close_side{
                display: none;
            }
        }

        .download-label {
        display: inline-block;
        font-family: Arial, sans-serif;
        cursor: pointer;
        color: #333;
    }

    .download-icon {
        width: 20px;
        height: 20px;
        vertical-align: middle;
        margin-right: 5px;
    }

    /* Estilos específicos para melhorar a acessibilidade */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .custom-file-input{
        display: none;
    }

    .dashboard_content{
        overflow: scroll;
    }

    table {
  border-collapse: collapse;
  width: 100%;
}

    th, td {
    border: 1px solid black;
    padding: 8px;
    text-align: left;
    }

    th {
    background-color: #f2f2f2;
    }

        

    </style>
</head>
<body>
    <div class="sidebar">
        <button class="close_side">X</button>
        <button class="open_side"><<</button>     
        <h2>Dashboard</h2>
        <ul>
            <li><a href="{{ route('dashboard.show', ['page' => 'home']) }}"><i class="fas fa-home"></i>Inicio</a></li>
            <li><a href="{{ route('dashboard.SumitPosts', ['page' => 'post_content']) }}"><i class="fas fa-user"></i>Listagem de Configurações</a></li>
            @if($test[0]->is_admin==1)
                 <li><a href="{{ route('dashboard.register', ['page' => 'register']) }}"><i class="fas fa-user-plus"></i>Registrar Editor</a></li>
            @endif
            <li><a href="{{ route('dashboard.contentConfig', ['page' => 'content_creation']) }}"><i class="fas fa-cog"></i> Criar config</a></li>
            <li><a href="{{ route('dashboard.wp', ['page' => 'wordpress_credentials']) }}"><i class="fas fa-key"></i> Inserir credenciais Wordpress</a></li>
            <li><a href="{{ route('dashboard.createPost', ['page' => 'post_creation']) }}"><i class="fas fa-file-alt"></i> Criar Conteúdo</a></li>
            <li><a href="{{ route('createDoc', ['page' => 'google_doc_creation']) }}"><i class="fab fa-google"></i> Google Docs</a></li>
            <li><a href="{{route('dashboard.uploadCsv',['page'=>'uploadCsv'])}}"><i class="fas fa-edit"></i>Importar config</a></li>
            <li><a href="{{ route('dashboard.configia', ['page' => 'ConfigGpt']) }}"><i class="fas fa-robot"></i> Configurar IA</a></li>
            <li class="quit"><a href="#"><i class="fas fa-sign-out-alt "></i> Sair</a></li>


            <!-- Adicione outras páginas conforme necessário -->
        </ul>
        
    </div>

    <div class="content">
        @yield('content')
    </div>

    <!-- Adicione este link no cabeçalho para Font Awesome -->



</body>

<script>
    const close_side=document.querySelector('.close_side')
    const open_side=document.querySelector('.open_side')
    const sidebar=document.querySelector('.sidebar')
    const quit_button=document.querySelector('.quit');


    quit_button.addEventListener('click',()=>{
        console.log('hello')
        deleteCookie('Editor');
    })


    function deleteCookie(name) {
        document.cookie = encodeURIComponent(name) + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=None; Secure';
    }

    close_side.addEventListener('click',(e)=>{
        sidebar.style="margin-left: -234px;";
        e.target.style='display:none';
        open_side.style="display:block";

    })

    open_side.addEventListener('click',(e)=>{
        sidebar.style="margin-left: 0;";
        e.target.style='display:none';
        close_side.style="display:block";

    })

</script>
</html>

@else

<?php
    // Redirecionar para a rota de login
    header("Location: " . route('login'));
    exit; // Importante: encerrar o script para evitar que o restante do código seja executado
?>

@endif

