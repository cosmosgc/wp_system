<!-- resources/views/upload.blade.php -->
@extends('layouts.app')

@php
    use App\Models\Editor;
    use App\Models\Wp_credential;
    use Illuminate\Support\Facades\Http;



    $valorCodificado = request()->cookie('editor');
    $user = explode('+', base64_decode($valorCodificado));
    $post_configs = Editor::where('name', $user[0])->first();
    //$credentials = Wp_credential::all();
    $credentials = Wp_credential::where('Editor_id', $post_configs->id)->get();
    //dd($credentials);
    //$uniqueDomains = [];
@endphp

@section('content')
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="height: 100%;"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" id="search-input" class="form-control" placeholder="Pesquisar por dominios">
            </div>
            <select id="domain-select" multiple class="form-control">
            @foreach($credentials->unique('wp_domain')->sortBy('wp_domain') as $credential)
                    <option value="{{$credential->wp_domain}}" >{{$credential->wp_domain}}</option>
            @endforeach
            </select>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div id="posts-table" class="border p-3">
                <h3>Escolha um domínio para carregar os posts recentes.</h3>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var domainSelect = document.getElementById('domain-select');
        var searchInput = document.getElementById('search-input');

        searchInput.addEventListener('keyup', function() {
            var searchTerm = this.value.toLowerCase();

            Array.from(domainSelect.options).forEach(function(option) {
                var optionText = option.textContent.toLowerCase();
                if (optionText.includes(searchTerm)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        });
    });
</script>


    <script>
        document.getElementById('domain-select').addEventListener('change', function() {
            var selectedDomain = this.value;
            if (!selectedDomain.startsWith("http://") && !selectedDomain.startsWith("https://")) {
                // If it doesn't have either prefix, assume "https://"
                selectedDomain = "https://" + selectedDomain;
            }
            var url = selectedDomain + "/wp-json/wp/v2/posts?per_page=10";

            fetch(url)
            .then(response => response.json())
            .then(posts => {
                var tableHtml = "<table><thead><tr><th>Titulo</th><th>Data</th><th>Yoast Keyword</th><th>Action</th></tr></thead><tbody>";

                posts.forEach(post => {
                    //não sei pegar o keyword no momento
                    tableHtml += `<tr><td>${post.title.rendered}</td><td>${post.date}</td><td id='yoastKey'>N/A</td><td><button class='btn btn-secondary' onclick="updateYoast('${selectedDomain}', '${post.id}', this)">Update Yoast Rank</button></td></tr>`;
                });

                tableHtml += "</tbody></table>";
                document.getElementById('posts-table').innerHTML = tableHtml;
            })
            .catch(error => {
                console.error('Error fetching posts:', error);
            });
        });

        function updateYoast(domain, postId, element) {
            element.classList.add('yoast-loading');
            var url = "/update_yoaust";
            var data = {
                domain: domain,
                post_id: postId,
                keyword: 'N/A'
            };
            if (domain.toLowerCase() === "localhost") {
                // If it is, add "http://"
                domain = "http://" + domain;
            } else if (!domain.startsWith("https://") && !domain.startsWith("http://")) {
                // If not "localhost" and doesn't start with "https://", add "https://"
                domain = "https://" + domain;
            }
            if (domain.endsWith("/")) {
                domain = domain.substring(0, domain.length - 1);
            }
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
                console.warn(data);
                if(data == 1){
                    console.error("retornou 1");
                    element.classList.remove('yoast-loading');
                    element.classList.remove('yoast-success');
                    // Add failure class to the element
                    element.classList.add('yoast-failure');
                        Swal.fire({
                            icon: 'error',
                            title: 'Um erro aconteceu',
                            html: `Houve um problema ao atualizar, ative o yoaust se está desligado e adicione uma keyword por favor. <a href='${domain}/wp-admin/post.php?post=${postId}&action=edit'>Clique aqui</a> para editar o post.`,
                            showConfirmButton: true,
                            confirmButtonText: 'continue'
                        });
                        return;
                }
                else if(data == false){
                    console.error("retornou false");
                    element.classList.remove('yoast-loading');
                    element.classList.remove('yoast-success');
                    // Add failure class to the element
                    element.classList.add('yoast-failure');
                        Swal.fire({
                            icon: 'error',
                            title: 'Um erro aconteceu',
                            html: `Não foi encontrado yoaust no site, ative o yoaust pelo menos uma vez e adicione uma keyword por favor. <a href='${domain}/wp-admin/post.php?post=${postId}&action=edit'>Clique aqui</a> para editar o post.`,
                            showConfirmButton: true,
                            confirmButtonText: 'continue'
                        });
                        return;
                }
                // Remove failure class if present
                element.classList.remove('yoast-loading');
                element.classList.remove('yoast-failure');
                // Add success class to the element
                element.classList.add('yoast-success');
                // Find the closest td element with id 'yoastKey'
                var yoastKeyElement = element.closest('tr').querySelector('td#yoastKey');
                // Update its content with the primary_focus_keyword from the response
                yoastKeyElement.textContent = data.primary_focus_keyword || 'N/A'; // Use primary_focus_keyword if available, otherwise 'N/A'

                if(!data.primary_focus_keyword){
                    element.classList.remove('yoast-loading');
                    element.classList.remove('yoast-success');
                    // Add failure class to the element
                    element.classList.add('yoast-failure');
                        Swal.fire({
                            icon: 'error',
                            title: 'Um erro aconteceu',
                            html: `Não foi encontrado uma keyword no post, então não terá efeito. <a href='${domain}/wp-admin/post.php?post=${postId}&action=edit'>Clique aqui</a> para editar o post.`,
                            showConfirmButton: true,
                            confirmButtonText: 'continue'
                        });
                }
                else{
                    // Display success message
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Seu rank Yoast foi atualizado com sucesso!',
                    //     showConfirmButton: false,
                    //     timer: 1500
                    // });
                    Swal.fire({
                        icon: 'success',
                        title: 'Seu rank Yoast foi atualizado com sucesso!',
                        showConfirmButton: true,
                        confirmButtonText: 'continue'
                    });

                }

            })
            .catch(error => {
                console.error('Error:', error.message);
                // Remove success class if present
                element.classList.remove('yoast-loading');
                element.classList.remove('yoast-success');
                // Add failure class to the element
                element.classList.add('yoast-failure');
                // Display error message
                Swal.fire({
                    icon: 'error',
                    title: 'Um erro aconteceu, não fez conexão um yoaust ativado',
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
        @keyframes pulse {
            0% {
                background-color: rgba(33, 28, 28, 1);
            }
            50% {
                background-color: rgba(33, 28, 28, 0.5);
            }
            100% {
                background-color: rgba(33, 28, 28, 1);
            }
        }

        .yoast-loading {
            animation: pulse 2s infinite;
        }
    </style>
@endsection
