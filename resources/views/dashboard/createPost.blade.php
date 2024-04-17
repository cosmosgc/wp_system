<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('content')
<h3>Gere aqui o conteúdo para uma das configurações</h3>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <div class="card card-medium">
                <div class="card-body">
                    <div class="mb-1">
                        <label for="topic" class="form-label">Tema</label>
                        <input type="text" class="form-control gpt_topics" id="topic" name="topic">
                    </div>
                    <div class="mb-1">
                        <label for="languages" class="form-label">Idioma</label>
                        <select class="form-select" id="languages" name="languages">
                            <option value="english">English</option>
                            <option value="portuguese">Portuguese</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="style" class="form-label">Estilo</label>
                        <select class="form-select" id="style" name="style">
                            <option value="casual">Casual</option>
                            <option value="blog">Blog</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="tone" class="form-label">Tom de escrita</label>
                        <select class="form-select" id="tone" name="tone">
                            <option value="first_person">Primeira pessoa</option>
                            <option value="eloquent">Eloquente</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="sections" class="form-label">Seções</label>
                        <input type="number" class="form-control" id="sections" name="sections">
                    </div>
                    <div class="mb-1">
                        <label for="paragraphs" class="form-label">Paragráfos</label>
                        <input type="number" class="form-control" id="paragraphs" name="paragraphs">
                    </div>
                    <button class="btn btn-primary gpt_submit">Gerar Texto</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>

        document.addEventListener('DOMContentLoaded',()=>{

        const topics = document.getElementById('topic').value;
        const languages=document.querySelector('#languages').value;
        const style=document.querySelector('#style').value;
        const tone = document.querySelector('#tone').value;
        const sections = document.querySelector('#sections').value;
        const paragraph=document.querySelector('#paragraphs').value;


        const gpt_button=document.querySelector('.gpt_submit');
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
        gpt_button.addEventListener('click',async ()=>{
            let topics = document.getElementById('topic').value;
            let languages = document.querySelector('#languages').value;
            let style = document.querySelector('#style').value;
            let tone = document.querySelector('#tone').value;
            let sections = document.querySelector('#sections').value;
            let paragraph = document.querySelector('#paragraphs').value;
            let data = {
                topic: topics,
                languages: languages,
                style: style,
                writing_tone: tone,
                sections: sections,
                paragraphs: paragraph,
                _token: csrfToken
            }
            console.log("log: ", data);
            const query= await fetch('/gpt_query',{
                method:'POST',
                body:JSON.stringify(data),
                headers:{"Content-Type":"application/json"}
            })

            const response=await query.json();
            if (query.ok) {
            // Exibe um alerta de sucesso usando SweetAlert2
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: 'Seu texto foi criado com sucesso!'
                });
        } else {
            // Exibe um alerta de falha usando SweetAlert2
            Swal.fire({
                title: 'Erro no processo',
                text: 'Deseja continuar?',
                icon: 'error',
                confirmButtonText: 'continue'
          })
        }
        })

        })

    </script>
@endsection
