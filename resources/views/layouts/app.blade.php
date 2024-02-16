<!-- resources/views/layouts/app.blade.php -->
@php  
    use App\Models\Editor;
    $test=Editor::all();

@endphp
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
        }

        .sidebar a:hover {
            background-color: #5EAD78; /* Verde mais escuro ao passar o mouse */
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
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="{{ route('dashboard.show', ['page' => 'home']) }}"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="{{ route('dashboard.show', ['page' => 'profile']) }}"><i class="fas fa-user"></i>Profile</a></li>
            <li><a href="{{ route('dashboard.SumitPosts', ['page' => 'post_content']) }}"><i class="fas fa-user"></i>Post Content</a></li>
            @foreach($test as $editor)
                @if($editor->is_admin == 1)
                     <li><a href="{{ route('dashboard.register', ['page' => 'register']) }}"><i class="fas fa-user-tie"></i>Register Employer</a></li>
                 @endif
            @endforeach

            <li><a href="{{route('dashboard.contentConfig',['page'=>'content_creation'])}}"><i class="fas fa-edit"></i>Create Content</a></li>
            <li><a href="{{route('dashboard.createPost',['page'=>'post_creation'])}}"><i class="fas fa-edit"></i>Post creation</a></li>
            <!-- Adicione outras páginas conforme necessário -->
        </ul>
    </div>

    <div class="content">
        @yield('content')
    </div>

    <!-- Adicione este link no cabeçalho para Font Awesome -->



</body>
</html>
