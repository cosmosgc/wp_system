<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Demand</title>
</head>
<body>
 @foreach($contents as $content)
    <p class="theme">{{$content->theme}}</p>
 @endforeach

 <button class="enviar">enviar</button>
 <button class="doc">Create Doc</button>

 <form action="/create_doc" method="post">
    @csrf
    <button type="submit" class="btn btn-primary">Conectar comSua conta google</button>
</form>

 <script>


const  gptQuery=document.querySelectorAll('.theme')
const send=document.querySelector(".enviar")
const docButton= document.querySelector(".doc")
let data=[]

const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
gptQuery.forEach((e)=>{
 data.push(e.textContent);

})

send.addEventListener('click',async ()=>{

    let body = {
                topic: data,
                _token: csrfToken
            }
            const query = await fetch('/gpt_query', {
                method: 'POST',
                body: JSON.stringify(body),
                headers: { "Content-Type": "application/json" }
            });

})


docButton.addEventListener('click',async ()=>{

    let body = {
                title: 'teste',
                _token: csrfToken
            }
            const query = await fetch('/create_doc', {
                method: 'POST',
                body: JSON.stringify(body),
                headers: { "Content-Type": "application/json" }
            });

    const response=await query.json()

    console.log(response);

})







 </script>
</body>
</html>