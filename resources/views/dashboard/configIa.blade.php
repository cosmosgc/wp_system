@extends('layouts.app')

@section('content')
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
                                        <option value="portuguese">Portuguese</option>
                                        <option value="spanish">Spanish</option>
                                        <option value="english">English</option>
                                    </select>
                                </div>

                                <label for="style">Estilo de escrita</label>
                                <div class="col-md-8">
                                    <select name="writing_style" id="style" class="style form-control">
                                        <option value="narrative">Narrative</option>
                                        <option value="descriptive">Descriptive</option>
                                        <option value="expository">Expository</option>
                                        <option value="persuasive">Persuasive</option>
                                        <option value="creative">Creative</option>
                                        <option value="objective">Objective</option>
                                        <option value="subjective">Subjective</option>
                                    </select>
                                </div>

                                <label for="tone" class="col-md-4 col-form-label text-md-right">Ton de Escrita</label>
                                <div class="col-md-8">
                                    <select name="writing_tone" id="tone" class="tone form-control">
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
                                </div>


                                <label for="paragraphs" class="col-md-4 col-form-label text-md-right">Número de paragrafos por seções</label>
                                <input type="number" name="paragraphs" id="paragraphs" class="form-control" value="2">
                                <label for="sections" class="col-md-4 col-form-label text-md-right">Número de seções por paragrafo</label>
                                <input type="number" name="sections" id="sections" class="form-control" value="2">

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
