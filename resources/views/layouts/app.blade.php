<!-- resources/views/layouts/app.blade.php -->
@php
    use App\Models\Editor;
    use Illuminate\Http\Request;


    $valorCodificado = request()->cookie('editor');
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
    <link rel="stylesheet" href="sweetalert2.min.css">

    <style>
        /* public/css/styles.css */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .header {
            width: 100%;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: rgba(33, 33, 33, 0.9); /* Dark background */
            padding: 10px 20px;
            transition: 0.8s;
            font-family: 'Roboto', sans-serif;
            box-shadow: 0px 2px 11px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px); /* Glass effect */
        }

        .header h2 {
            color: #FFFFFF; /* White text color */
            text-align: left;
            display: inline-block;
        }

        .header ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .header li {
            padding: 8px;
            text-align: left;
            transition: 0.3s;
        }

        .header a {
            text-decoration: none;
            color: #FFFFFF; /* White link color */
            padding: 5px;
            display: block;
            transition: 0.3s;
        }

        .header a:hover {
            background-color: rgba(50, 50, 50, 0.9); /* Darker background on hover */
            transition: 0.5s;
            border-radius: 10px;
        }

        .header li:hover {
            transform: scale(1.05);
        }

        .content {
            margin-top: 70px;
            padding: 20px;
        }

        .header i {
            margin-right: 8px;
        }

        .header .fa, .header .fas {
            align-self: center;
        }

        li a {
            display: flex;
            align-items: center;
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

        /* Specific styles to improve accessibility */
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

        .custom-file-input {
            display: none;
        }

        .dashboard_content {
            overflow: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0px 0px 9px 0px black;
            word-wrap: break-word;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: left;
            font-size: small;
        }

        th {
            background-color: #daf3e4;
        }

        tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0e0e0a8;
        }

        @media only screen and (max-width: 600px) {
            th, td {
                font-size: 14px;
            }
        }

        .card-medium {
            box-shadow: 4px 4px 12px 0px black;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .token_buttons {
            display: flex;
            gap: 5%;
            justify-content: center;
            align-content: center;
        }

        li {
            color: white;
        }

        .arrow {
            transform: rotate(90deg);
            display: inline-block;
            transition: 0.6s;
        }

        .arrow_up {
            transform: rotate(-90deg);
            transition: 0.6s;
        }

        .configs_content {
            display: none;
        }

        .open_box {
            padding-left: 6% !important;
        }

        .open {
            display: block;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- <button class="close_side">X</button>
        <button class="open_side"><<</button>
        <h2>Dashboard</h2> -->
        <ul>
            <li><a href="{{ route('dashboard.show', ['page' => 'home']) }}"><i class="fas fa-home"></i>Inicio</a></li>

            <li style="cursor: pointer" class="open_box"><i class="fas fa-file-alt"></i>Artigos <span class="arrow">></span></li>

            <div class="configs_content">
                <ol><a href="{{ route('dashboard.SumitPosts', ['page' => 'post_content']) }}"><i class="fas fa-upload"></i>Publicar em sites</a></ol>
                <ol><a href="{{ route('dashboard.contentConfig', ['page' => 'content_creation']) }}"><i class="fas fa-pen"></i>Criar Artigo</a></ol>
                <ol><a href="{{route('dashboard.uploadCsv',['page'=>'uploadCsv'])}}"><i class="fas fa-file-excel"></i>Importar CSV</a></ol>
            </div>

            <li style="cursor: pointer" class="open_box"><i class="fas fa-cog"></i>Configurações <span class="arrow">></span></li>

            <div class="configs_content">
                <ol><a href="{{ route('dashboard.wp', ['page' => 'wordpress_credentials']) }}"><i class="fas fa-registered"></i>Registrar Sites</a></ol>
                <ol><a href="{{ route('importSite', ['page' => 'importSite']) }}"><i class="fas fa-globe"></i>Importar sites</a></ol>

                <ol><a href="{{ route('listCredential', ['page' => 'list_wp_credentials']) }}"><i class="fas fa-key"></i>Lista de Sites</a></ol>

                @if($test[0]->is_admin==1)
                <ol><a href="{{ route('dashboard.register', ['page' => 'register']) }}"><i class="fas fa-user-plus"></i>Registrar Editor</a></ol>
                <ol><a href="{{ route('listEditor', ['page' => 'editor_list']) }}"><i class="fas fa-users"></i>Lista de editores</a></ol>
                @endif
                <ol><a href="{{ route('dashboard.configia', ['page' => 'ConfigGpt']) }}"><i class="fas fa-key"></i>Configurações de Ia</a></ol>
                {{-- @if($test[0]->is_admin==1)
                <ol><a href="{{ route('listIaCredentials', ['page' => 'listGptToken']) }}"><i class="fas fa-list"></i>Listar Tokens</a></ol>
                @endif --}}
                <ol><a href="{{ route('dashboard.gDriveConfig', ['page' => 'dashboard.gDriveConfig']) }}"><i class="fas fa-id-card"></i>Criar credenciais Google</a></ol>
                <ol><a href="{{ route('yoastforce', ['page' => 'yoastforce']) }}"><i class="fas fa-id-card"></i>youstforce</a></ol>
                <ol><a href="{{ route('projectCreation', ['page' => 'criar_projetos']) }}"><i class="fas fa-coffee"></i>Criar Projeto</a></ol>
                <ol><a href="{{ route('projectList', ['page' => 'listar_projetos']) }}"><i class="fas fa-coffee"></i>Lista de Projetos</a></ol>

            </div>

            <li class="quit"><a href="#" onclick="logoff()"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
        </ul>


    </div>

    <div class="content">
        @yield('content')
    </div>

    <!-- Adicione este link no cabeçalho para Font Awesome -->



</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const close_side=document.querySelector('.close_side')
    const open_side=document.querySelector('.open_side')
    const sidebar=document.querySelector('.sidebar')
    const content=document.querySelector('.content')
    const quit_button=document.querySelector('.quit');


    quit_button.addEventListener('click',()=>{
        logoff();
    })


    function deleteCookie(name) {
        // Convert the cookie name to lowercase
        var lowercaseName = name.toLowerCase();

        // Delete the cookie with both the original and lowercase names, with HttpOnly attribute
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=None; domain=' + location.hostname + '; Secure; HttpOnly';
        document.cookie = lowercaseName + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=None; domain=' + location.hostname + '; Secure; HttpOnly';

        // Log the deleted cookies
        console.log(get_cookie(name));
        console.log(get_cookie(lowercaseName));
    }


    function get_cookie(name){
        return document.cookie.split(';').some(c => {
            return c.trim().startsWith(name + '=');
        });
    }

    close_side.addEventListener('click', () => {
        sidebar.classList.add('closed');
        content.classList.add('expanded');
        sidebar.classList.remove('open');
        content.classList.remove('minimize');
        // close_side.style.display = 'none';
        // open_side.style.display = 'block';
    });

    open_side.addEventListener('click', () => {
        sidebar.classList.remove('closed');
        content.classList.remove('expanded');
        sidebar.classList.add('open');
        content.classList.add('minimize');
        // close_side.style.display = 'block';
        // open_side.style.display = 'none';
    });
    function logoff() {
        // Make an AJAX request to the logoff route using fetch
        fetch('/quit', {
            method: 'get',
            headers: {
                'Content-Type': 'application/json',
                // You may include additional headers if needed
            },
        })
        .then(response => {
            // Check if the response status is 200 (OK)
            if (response.ok) {
                // Reload the page
                window.location.href = '/';
            } else {
                // Handle non-OK responses if needed
                console.error('Logoff failed with status:', response.status);
            }
        })
        .catch(error => {
            // Handle errors if needed
            console.error('Error during logoff:', error);
        });
    }

      const menuOpenbox=document.querySelectorAll(".open_box");
      const openBoxContent=document.querySelectorAll(".configs_content")
      const arrow=document.querySelectorAll(".arrow");
      menuOpenbox.forEach((e,i)=>{
        e.addEventListener('click',()=>{
            openBoxContent[i].classList.toggle("open");
            arrow[i].classList.toggle("arrow_up");
        })

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

