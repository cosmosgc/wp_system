<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="dashboard-content">
         <input class="gpt_topics" type="text" name="topic" id="topic">
         <select name="languages" id="languages">
            <option value="english">English</option>
            <option value="portuguese">Portuguese</option>
         </select>
         <select name="style" id="style">
            <option value="casual">Casual</option>
            <option value="Blog">Blog</option>
         </select>
         <select name="writing_tone" id="tone">
            <option value="first person">Firt person</option>
            <option value="eloquent">Eloquent</option>
         </select>
         <input type="number" name="sections" id="sections">
         <input type="number" name="paragraphs" id="paragraphs">
        <button class="gpt_submit">Gerar texto</button>
    </div>

    <script>
        const topics = document.querySelector('.gpt_topics').value;
        const languages=document.querySelector('#languages').value;
        const style=document.querySelector('#style').value;
        const tone = document.querySelector('#tone').value;
        const sections = document.querySelector('#sections').value;
        const paragraph=document.querySelector('#paragraphs').value;


        const gpt_button=document.querySelector('.gpt_submit');
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
        gpt_button.addEventListener('click',async ()=>{
            console.log(topics)
            console.log(languages)
            console.log(style);
            console.log(tone);
            console.log(sections)
            console.log(paragraph);
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
            console.log(response);
        })
    </script>
@endsection