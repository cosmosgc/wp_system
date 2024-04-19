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
                    tableHtml += "<tr><td>" + post.title.rendered + "</td><td>" + post.date + "</td><td>N/A</td><td><button class='btn btn-secondary' onclick='updateYoast(\"" + selectedDomain + "\", \"" + post.id + "\")'>Update Yoast Rank</button></td></tr>";
                });

                tableHtml += "</tbody></table>";
                document.getElementById('posts-table').innerHTML = tableHtml;
            })
            .catch(error => {
                console.error('Error fetching posts:', error);
            });
        });

        function updateYoast(domain, postId) {
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
                    console.log('Success');
                } else {
                    console.error('Error updating Yoast rank');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
        }
    </script>
@endsection
