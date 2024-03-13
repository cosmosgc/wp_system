@extends('layouts.app')
@php
use App\Models\Editor;
//use App\Models\Wp_credential;
$valorCodificado = request()->cookie('editor');
//$credentials=Wp_credential::all();


$user=explode('+',base64_decode($valorCodificado));
$user_session=Editor::where('name',$user[0])->get();
$user_id = $user_session[0]->id
@endphp
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <div class="card card-medium">
                <div class="card-body">
                    <h3>Credenciais google</h3>
                    <form action="/create_google_credential" method="post">
                        @csrf
                        <div class="mb-3">
                            <input  type="text" class="form-control" name="client_id" id="client_id" >
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="project_id" id="project_id" placeholder="project_id">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="auth_uri" id="auth_uri" placeholder="auth_uri">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="token_uri" id="token_uri" placeholder="token_uri">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="auth_provider_x509_cert_url" id="auth_provider_x509_cert_url" placeholder="auth_provider_x509_cert_url">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="client_secret" id="client_secret" placeholder="client_secret">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="redirect_uris" id="redirect_uris" placeholder="redirect_uris">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="api_key" id="api_key" placeholder="api_key">
                        </div>
                        <div class="mb-3">
                            <input disabled type="text" class="form-control" name="editor_id" id="editor_id" placeholder="{{$user_id}}">
                        </div>
                        <button type="submit" class="btn btn-primary">Criar credenciais google</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
