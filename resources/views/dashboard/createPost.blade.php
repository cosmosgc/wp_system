<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <div class="card card-medium">
                <div class="card-body">
                    <div class="mb-1">
                        <label for="topic" class="form-label">Topic</label>
                        <input type="text" class="form-control gpt_topics" id="topic" name="topic">
                    </div>
                    <div class="mb-1">
                        <label for="languages" class="form-label">Languages</label>
                        <select class="form-select" id="languages" name="languages">
                            <option value="english">English</option>
                            <option value="portuguese">Portuguese</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="style" class="form-label">Style</label>
                        <select class="form-select" id="style" name="style">
                            <option value="casual">Casual</option>
                            <option value="blog">Blog</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="tone" class="form-label">Writing Tone</label>
                        <select class="form-select" id="tone" name="tone">
                            <option value="first_person">First Person</option>
                            <option value="eloquent">Eloquent</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="sections" class="form-label">Sections</label>
                        <input type="number" class="form-control" id="sections" name="sections">
                    </div>
                    <div class="mb-1">
                        <label for="paragraphs" class="form-label">Paragraphs</label>
                        <input type="number" class="form-control" id="paragraphs" name="paragraphs">
                    </div>
                    <button class="btn btn-primary gpt_submit">Generate Text</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>

        document.addEventListener('DOMContentLoaded',()=>{

        const topics = document.querySelector('.gpt_topics').value;
        const languages=document.querySelector('#languages').value;
        const style=document.querySelector('#style').value;
        const tone = document.querySelector('#tone').value;
        const sections = document.querySelector('#sections').value;
        const paragraph=document.querySelector('#paragraphs').value;


        const gpt_button=document.querySelector('.gpt_submit');
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
        gpt_button.addEventListener('click',async ()=>{
            const query= await fetch('/gpt_query',{
                method:'POST',
                body:JSON.stringify({
                    topic:topics,
                    languages:languages,
                    style:style,
                    writing_tone:tone,
                    sections:sections,
                    paragraphs:paragraph,
                    _token: csrfToken
                }),
                headers:{"Content-Type":"application/json"}
            })

            const response=await query.json();
            if (query.ok) {
            // Exibe um alerta de sucesso usando SweetAlert2
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: 'Your text was sucessfully created'
                });
        } else {
            // Exibe um alerta de falha usando SweetAlert2
            Swal.fire({
                title: 'Error on the process',
                text: 'Do you want to continue',
                icon: 'error',
                confirmButtonText: 'continue'
          })
        }
        })

        })

    </script>
@endsection