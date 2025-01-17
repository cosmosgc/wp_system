@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-medium">
                <div class="card-header">{{ __('Insert your OpenAI token') }}</div>

                <div class="card-body">
              
                    <form method="POST" action="{{ route('insertIaToken') }}">
                        
                        <div class="form-group row">
                            <label for="token" class="col-md-4 col-form-label text-md-right">{{ __('Token') }}</label>

                            <div class="col-md-6">
                                <input id="token" type="text" class="form-control @error('token') is-invalid @enderror" name="token" required autocomplete="token" autofocus value="{{isset($ia_token[0])?$ia_token[0]->open_ai:null}}">
                                <label for="language">Linguagem</label>
                                <select name="language" id="language" class="language">
                                    <option value="portuguese">Postuguês</option>
                                    <option value="spanish">Espanhol</option>
                                    <option value="English">Inglês</option>
                                </select>
                                <label for="style">Estilo de escrita</label>
                                <select name="writing_style" id="style" class="style">
                                    <option value="narrative">Narrative</option>
                                    <option value="descriptive">Descriptive</option>
                                    <option value="expository">Expository</option>
                                    <option value="persuasive">Persuasive</option>
                                    <option value="creative">Creative</option>
                                    <option value="objective">Objective</option>
                                    <option value="subjective">Subjective</option>
                                </select>
                                <label for="tone">Ton de Escrita</label>
                                <select name="writing_tone" id="tone" class="tone">
                                    <option value="casual">Casual</option>
                                    <option value="eloquent">Eloquent</option>
                                    <option value="informal">Informal</option>
                                    <option value="optimistic">Optimistic</option>
                                    <option value="worried">Worried</option>
                                    <option value="friendly">Friendly</option>
                                    <option value="curious">Curious</option>
                                    <option value="assertive">Assertive</option>
                                    <option value="encouraging">Encouraging</option>
                                    <option value="surprised">Surprised</option>
                                    <option value="neutral">Neutral</option>
                                </select>

                                <label for="paragraphs">Número de paragrafos por seções</label>
                                <input type="number" name="paragraphs" id="paragraphs">
                                <label for="sections">Número de seções por paragrafo</label>
                                <input type="number" name="sections" id="sections">

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
                                    {{ __('Insert Token/Update Token') }}
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
