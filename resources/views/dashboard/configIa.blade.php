@extends('layouts.app')

@section('content')
@php
use App\Models\Editor;
use App\Models\Ia_credential;

$retrieve_user=Editor::where('name',$editor)->get();

$retrieve_token=Ia_credential::where('Editor_id',$retrieve_user[0]->id)->get();
$token_exists = !$retrieve_token->isEmpty();
$ia_cred = $retrieve_token->first();
$ia_cred = $ia_cred ?? new Ia_credential();
//dd($ia_cred);
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-medium">
                <div class="card-header">{{ __('Insira seu Token OpenAI') }}</div>

                <div class="card-body">

                    <form method="POST" action="{{ route('insertIaToken') }}">

                        <div class="form-group row">

                            <div class="col-md-12">
                                <label for="token" class="col-md-4 col-form-label text-md-right">{{ __('Token') }}</label>

                                <input id="token" type="text" class="form-control @error('token') is-invalid @enderror" name="token" required autocomplete="token" autofocus value="{{isset($ia_token)?$ia_token->open_ai:null}}">
                                <label for="language" class="col-md-4 col-form-label text-md-right">Linguagem</label>
                                <div class="col-md-8">
                                    <select name="language" id="language" class="form-control">
                                        <option value="portuguese" {{ $ia_cred->language == 'portuguese' ? 'selected' : '' }}>Portuguese</option>
                                        <option value="spanish" {{ $ia_cred->language == 'spanish' ? 'selected' : '' }}>Spanish</option>
                                        <option value="english" {{ $ia_cred->language == 'english' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>

                                <label for="style">Estilo de escrita</label>
                                <div class="col-md-8">
                                    <select name="writing_style" id="writing_style" class="style form-control">
                                        <option value="narrative" {{ $ia_cred->writing_style == 'narrative' ? 'selected' : '' }}>Narrative</option>
                                        <option value="descriptive" {{ $ia_cred->writing_style == 'descriptive' ? 'selected' : '' }}>Descriptive</option>
                                        <option value="expository" {{ $ia_cred->writing_style == 'expository' ? 'selected' : '' }}>Expository</option>
                                        <option value="persuasive" {{ $ia_cred->writing_style == 'persuasive' ? 'selected' : '' }}>Persuasive</option>
                                        <option value="creative" {{ $ia_cred->writing_style == 'creative' ? 'selected' : '' }}>Creative</option>
                                        <option value="objective" {{ $ia_cred->writing_style == 'objective' ? 'selected' : '' }}>Objective</option>
                                        <option value="subjective" {{ $ia_cred->writing_style == 'subjective' ? 'selected' : '' }}>Subjective</option>
                                    </select>
                                </div>

                                <label for="tone" class="col-md-4 col-form-label text-md-right">Ton de Escrita</label>
                                <div class="col-md-8">
                                    <select name="writing_tone" id="tone" class="tone form-control">
                                        <option value="casual" {{ $ia_cred->writing_tone == 'casual' ? 'selected' : '' }}>Casual</option>
                                        <option value="eloquent" {{ $ia_cred->writing_tone == 'eloquent' ? 'selected' : '' }}>Eloquent</option>
                                        <option value="informal" {{ $ia_cred->writing_tone == 'informal' ? 'selected' : '' }}>Informal</option>
                                        <option value="optimistic" {{ $ia_cred->writing_tone == 'optimistic' ? 'selected' : '' }}>Optimistic</option>
                                        <option value="worried" {{ $ia_cred->writing_tone == 'worried' ? 'selected' : '' }}>Worried</option>
                                        <option value="friendly" {{ $ia_cred->writing_tone == 'friendly' ? 'selected' : '' }}>Friendly</option>
                                        <option value="curious" {{ $ia_cred->writing_tone == 'curious' ? 'selected' : '' }}>Curious</option>
                                        <option value="assertive" {{ $ia_cred->writing_tone == 'assertive' ? 'selected' : '' }}>Assertive</option>
                                        <option value="encouraging" {{ $ia_cred->writing_tone == 'encouraging' ? 'selected' : '' }}>Encouraging</option>
                                        <option value="surprised" {{ $ia_cred->writing_tone == 'surprised' ? 'selected' : '' }}>Surprised</option>
                                        <option value="neutral" {{ $ia_cred->writing_tone == 'neutral' ? 'selected' : '' }}>Neutral</option>
                                    </select>
                                </div>


                                <label for="paragraphs" class="col-md-4 col-form-label text-md-right">Número de paragrafos por seções</label>
                                <input type="number" name="paragraphs" id="paragraphs" class="form-control" value="{{ isset($ia_cred->pagraphs) ? $ia_cred->pagraphs : '2' }}">
                                <label for="sections" class="col-md-4 col-form-label text-md-right">Número de seções por paragrafo</label>
                                <input type="number" name="sections" id="sections" class="form-control" value="{{ isset($ia_cred->sections) ? $ia_cred->sections : '2' }}">

                                @error('token')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="form-group row mb-0">
                            <div class="token_buttons" class="col-md-6 offset-md-4">
                                <input type="hidden" name="editor" value="{{$editor}}">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-primary">
                                        {{ $token_exists ? __('Atualizar Token') : __('Inserir Token') }}
                                    </button>

                            </form>
                                <form action="{{ route('deleteToken', isset($ia_token[0])?$ia_token[0]->id:'') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" type="submit">{{ __('Remover Token') }}</button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
