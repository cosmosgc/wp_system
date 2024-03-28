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
    @foreach($credentials as $credential)
        <h3>{{$credential->wp_domain}}</h3>
        <table>
            <thead>
                <tr>
                    <th>Titulo</th>
                    <th>Data</th>
                    <th>Yoast Keyword</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $response = Http::get("{$credential->wp_domain}/wp-json/wp/v2/posts?per_page=10");
                    $posts = $response->json();

                @endphp
                @foreach ($posts as $post)
                    @php
                        // Fetch Yoast Keyword using custom WordPress REST API endpoint
                        $keywordResponse = Http::get("{$credential->wp_domain}/wp-json/wp_manage/v1/get_keyword/?post_id={$post['id']}");
                        $keywordData = $keywordResponse->json();
                        $keyword = isset($keywordData['primary_focus_keyword']) ? $keywordData['primary_focus_keyword'] : 'N/A';
                    @endphp
                    <tr>
                        <td>{{$post['title']['rendered']}}</td>
                        <td>{{$post['date']}}</td>
                        <td>{{$keyword}}</td>
                        <td><button class="btn btn-secondary" onclick="update_yoast('{{$credential->wp_domain}}', '{{$post['id']}}', '{{$keyword}}')">Atualizar Yoast Rank</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
    <script>
        function update_yoast(domain, id, keyword){

            //use /update_yoaust
            url = "/update_yoaust"
            data = {
                    domain: domain,
                    post_id: id,
                    keyword: keyword
                };
                console.log(data);
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (response.ok) {
                    console.log('sucesso', response.json());
                } else {
                    // Handle error response
                    console.log('error')
                }
            })
            .catch(error => {
                // Handle fetch error
                console.log('error')
            });
        }
    </script>
@endsection
