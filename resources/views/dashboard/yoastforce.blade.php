<!-- resources/views/upload.blade.php -->
@extends('layouts.app')

@php
    use App\Models\Editor;
    use App\Models\Wp_credential;
    use Illuminate\Support\Facades\Http;

    $credentials = Wp_credential::all();

    $valorCodificado = request()->cookie('editor');
    $user = explode('+', base64_decode($valorCodificado));
    $post_configs = Editor::where('name', $user[0])->get();
@endphp

@section('content')
    <select id="domain-select">
        @foreach($credentials as $credential)
            <option value="{{$credential->wp_domain}}">{{$credential->wp_domain}}</option>
        @endforeach
    </select>

    <div id="posts-table">
        <h3>Escolha um dominio para carregas os posts recentes.</h3>
    </div>

    <script>
        document.getElementById('domain-select').addEventListener('change', function() {
            var selectedDomain = this.value;
            var url = selectedDomain + "/wp-json/wp/v2/posts?per_page=10";

            fetch(url)
            .then(response => response.json())
            .then(posts => {
                var tableHtml = "<table><thead><tr><th>Title</th><th>Date</th><th>Yoast Keyword</th><th>Action</th></tr></thead><tbody>";

                posts.forEach(post => {
                    //não sei pegar o keyword no momento
                    tableHtml += "<tr><td>" + post.title.rendered + "</td><td>" + post.date + "</td><td id='yoastKey'>N/A</td><td><button class='btn btn-secondary' onclick='updateYoast(\"" + selectedDomain + "\", \"" + post.id + "\", this)'>Update Yoast Rank</button></td></tr>";
                });

                tableHtml += "</tbody></table>";
                document.getElementById('posts-table').innerHTML = tableHtml;
            })
            .catch(error => {
                console.error('Error fetching posts:', error);
            });
        });

        function updateYoast(domain, postId, element) {
            var url = "/update_yoaust";
            var data = {
                domain: domain,
                post_id: postId,
                //keyword: 'N/A'
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (response.ok) {
                    return response.json(); // Parse response JSON
                } else {
                    throw new Error('Arro ao atualizar o rank Yoast');
                }
            })
            .then(data => {
                console.log('Success');
                // Remove failure class if present
                element.classList.remove('yoast-failure');
                // Add success class to the element
                element.classList.add('yoast-success');
                // Find the closest td element with id 'yoastKey'
                var yoastKeyElement = element.closest('tr').querySelector('td#yoastKey');
                // Update its content with the primary_focus_keyword from the response
                yoastKeyElement.textContent = data.primary_focus_keyword || 'N/A'; // Use primary_focus_keyword if available, otherwise 'N/A'
                if(!data.primary_focus_keyword){
                    element.classList.remove('yoast-success');
                    // Add failure class to the element
                    element.classList.add('yoast-failure');
                        Swal.fire({
                            icon: 'error',
                            title: 'Um erro aconteceu',
                            text: "Não foi encontrado uma keyword no post, então não terá efeito",
                            showConfirmButton: true,
                            confirmButtonText: 'continue'
                        });
                }
                else{
                    // Display success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Seu rank Yoast foi atualizado com sucesso!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

            })
            .catch(error => {
                console.error('Error:', error.message);
                // Remove success class if present
                element.classList.remove('yoast-success');
                // Add failure class to the element
                element.classList.add('yoast-failure');
                // Display error message
                Swal.fire({
                    icon: 'error',
                    title: 'Um erro aconteceu',
                    text: error.message,
                    showConfirmButton: true,
                    confirmButtonText: 'continue'
                });
            });
        }

    </script>
    <style>
        .yoast-success {
            background-color: #dff0d8 !important;
            color: #3c763d !important;
            border: 1px solid #d6e9c6 !important;
        }
        .yoast-success::after {
            content: '\2714'; /* Unicode for checkmark symbol */
            position: absolute;
            color: #3c763d;
        }
        .yoast-failure {
            background-color: #f2dede !important;
            color: #a94442 !important;
            border: 1px solid #ebccd1 !important;
        }
        .yoast-failure::after {
            content: '\2716'; /* Unicode for cross symbol */
            position: absolute;
            color: #a94442;
        }
    </style>
@endsection
