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
