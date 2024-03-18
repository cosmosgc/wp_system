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
    <p class="theme" data-id="{{$content->id}}">{{$content->theme}}</p>
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
let theme=[]

const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
gptQuery.forEach((e)=>{
    const id =e.getAttribute("data-id");
 data.push(id);
 theme.push(e.textContent);

})

send.addEventListener('click',async ()=>{

    let body = {
                title:theme,
                _token: csrfToken
            }
            const query = await fetch('/gpt_query', {
                method: 'POST',
                body: JSON.stringify(body),
                headers: { "Content-Type": "application/json" }
            });

})


docButton.addEventListener('click',async ()=>{
    let folderLink='https://drive.google.com/drive/folders/1iGQA7TFu1f7mp3r0SY7MTNDqPF72Ucl8?usp=sharing'
    const folderId=folderLink.split('/folders/');
    const folder=folderId[1];
    const realForlderId=folder.split('?usp=sharing');
    let body = {
                title: 'teste',
                folder_id:realForlderId[0],
                _token: csrfToken
            }
            const query = await fetch('/create_doc', {
                method: 'POST',
                body: JSON.stringify(body),
                headers: { "Content-Type": "application/json" }
            });

    const response=await query.json()

   // console.log(realForlderId[0]);

})







 </script>
</body>
</html>