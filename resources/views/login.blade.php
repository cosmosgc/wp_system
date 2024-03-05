<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Login</div>

                <div class="card-body">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="name" type="text" class="form-control" name="name"  required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <button type="submit" class="submit btn btn-primary">Login</button>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
        $token=localStorage.getItem('Editor')
        if ($token){
        // Se não estiver presente, redireciona para a página de dashboard
        
         window.location.href = location.href+'dashboard';
    }else{
        return
    }
</script>

<script>
    const name = document.getElementById("name");
    const password=document.getElementById("password");
    const submitButton = document.querySelector(".submit");
    const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
    console.log(csrfToken);

    submitButton.addEventListener('click',async ()=>{
            localStorage.setItem('Editor',  btoa(csrfToken));
        const validate_query=await fetch('/validate',{
            method:'POST',
            body:JSON.stringify({name:name.value,password:password.value,_token:csrfToken}),
            headers:{"Content-Type":"application/json"}
        })


        if (validate_query.status === 200) {
        // O status da resposta é 200 (OK), então você pode prosseguir com o código
        location.href=location.href+'dashboard';
    } else {
        // O status da resposta não é 200 (OK), então houve algum erro
        console.error('Erro ao validar credenciais:', validate_query.status);
    }

    })

</script>
</body>
</html>
