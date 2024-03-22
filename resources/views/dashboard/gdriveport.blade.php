@extends('layouts.app')
@php
use App\Models\Editor;
use Illuminate\Http\Request;
use App\Models\Drive_credential;

//use App\Models\Wp_credential;
$valorCodificado = request()->cookie('editor');



$user=explode('+',base64_decode($valorCodificado));
$user_session=Editor::where('name',$user[0])->get();
$user_id = $user_session[0]->id;

$credential=Drive_credential::all()->first();
$create_new_gc = false;
if(empty($credential)){
    $create_new_gc = true;
    $credential = new Drive_credential();
}
@endphp
@section('content')
<!-- Bootstrap JavaScript file -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .two-column-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
}

.two-column-form .form-group {
    flex: 1 1 50%;
    padding-right: 15px;
}
.two-column-form button {
    flex: 1 1 100%;
    margin-top: 10px;
}
.two-column-form label {
    cursor: pointer;
}

@media (max-width: 768px) {
    .two-column-form .form-group {
        flex: 1 1 100%;
        padding-right: 0;
    }
}

</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-medium">
                <div class="card-body">
                    <h3>Credenciais google</h3>
                    <form id="google_credential_form" class="two-column-form" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="client_id" class="form-label" data-bs-toggle="popover" data-bs-placement="right" title="Client ID" data-bs-content="Este é o ID fornecido pelo Google para seu aplicativo cliente.">Client ID</label>
                            <input type="text" class="form-control" name="client_id" id="client_id" placeholder="Client ID" value="{{ $credential->client_id }}">
                        </div>
                        <div class="mb-3">
                            <label for="project_id" class="form-label" data-bs-toggle="popover" data-bs-placement="right" title="Project ID" data-bs-content="Este é o ID do projeto associado às suas credenciais.">Project ID</label>
                            <input type="text" class="form-control" name="project_id" id="project_id" placeholder="Project ID" value="{{ $credential->project_id }}">
                        </div>
                        <div class="mb-3">
                            <label for="auth_uri" class="form-label" data-bs-toggle="popover" data-bs-placement="right" title="Auth URI" data-bs-content="Esta é a URI de autorização utilizada pelo Google OAuth2.">Auth URI</label>
                            <input type="text" class="form-control" name="auth_uri" id="auth_uri" placeholder="Auth URI" value="{{ $credential->auth_uri }}">
                        </div>
                        <div class="mb-3">
        <label for="token_uri" class="form-label" data-bs-toggle="popover" data-bs-placement="right" title="Token URI" data-bs-content="Este é o URI usado para obter um token de acesso do servidor de autorização.">Token URI</label>
        <input type="text" class="form-control" name="token_uri" id="token_uri" placeholder="Token URI" value="{{ $credential->token_uri }}">
    </div>
                    <div class="mb-3">
                        <label for="auth_provider_x509_cert_url" class="form-label" data-bs-toggle="popover" data-bs-placement="right" title="Auth Provider X509 Cert URL" data-bs-content="Esta é a URL do certificado usado pelo provedor de autenticação para verificar a autenticidade do token.">Auth Provider X509 Cert URL</label>
                        <input type="text" class="form-control" name="auth_provider_x509_cert_url" id="auth_provider_x509_cert_url" placeholder="Auth Provider X509 Cert URL" value="{{ $credential->auth_provider_x509_cert_url }}">
                    </div>
                    <div class="mb-3">
                        <label for="client_secret" class="form-label" data-bs-toggle="popover" data-bs-placement="right" title="Client Secret" data-bs-content="Esta é a chave secreta atribuída ao seu aplicativo cliente pelo servidor de autenticação.">Client Secret</label>
                        <input type="text" class="form-control" name="client_secret" id="client_secret" placeholder="Client Secret" value="{{ $credential->client_secret }}">
                    </div>
                    <div class="mb-3">
                        <label for="redirect_uris" class="form-label" data-bs-toggle="popover" data-bs-placement="right" title="Redirect URIs" data-bs-content="Estes são os URIs para os quais o servidor de autenticação redireciona o usuário após a autenticação bem-sucedida.">Redirect URIs</label>
                        <input type="text" class="form-control" name="redirect_uris" id="redirect_uris" placeholder="Redirect URIs" value="{{ $credential->redirect_uris }}">
                    </div>
                    <div class="mb-3">
                        <label for="api_key" class="form-label" data-bs-toggle="popover" data-bs-placement="right" title="API Key" data-bs-content="Esta é a chave usada para autenticar sua aplicação ao fazer solicitações à API.">API Key</label>
                        <input type="text" class="form-control" name="api_key" id="api_key" placeholder="API Key" value="{{ $credential->api_key }}">
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="editor_id" value="{{$user_id}}" >
                        <label for="editor_id" class="form-label" data-bs-toggle="popover" data-bs-placement="right" title="Editor ID" data-bs-content="Este é o identificador exclusivo atribuído ao editor.">Editor ID</label>
                        <input disabled type="text" class="form-control" id="editor_id" placeholder="{{$user_id}}" value="{{ $credential->editor_id }}">
                    </div>
                    <input disabled type="text" class="form-control" id="id" placeholder="{{$user_id}}" value="{{ $credential->id }}">

                    <button type="submit" class="btn btn-primary">
                        @if($create_new_gc)
                            Criar credenciais Google
                        @else
                            Atualizar credenciais Google
                        @endif
                    </button>

                    </form>

                    <a class="col-md-12" href="/auth/google"><button type="submit" class="btn btn-primary">Login Google</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    });

</script>

<script>
    document.getElementById('google_credential_form').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form submission
        var form = this;
        var url = @json($create_new_gc ? '/create_google_credential' : '/update_google_credential');

        fetch(url, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (response.ok) {
                console.log('Success');
            } else {
                console.error('Error', response);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>

@endsection
