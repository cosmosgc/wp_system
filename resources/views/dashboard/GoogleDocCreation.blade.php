@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <div class="card">
                <div class="card-body">
                    <h3>Create Document</h3>
                    <form action="/create_doc" method="post">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" name="title" id="title" placeholder="Title of the document">
                        </div>
                        <button type="submit" class="btn btn-primary">Create Document</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h3>Retrieve Document</h3>
                    <form action="/process_doc" method="post">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" name="google_docs" id="google_docs" placeholder="Document ID">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="title" id="title" placeholder="Title of the document">
                        </div>
                        <button type="submit" class="btn btn-primary">Insert Document Text</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection