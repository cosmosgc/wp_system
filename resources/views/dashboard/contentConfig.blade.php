<!-- resources/views/create_post_content.blade.php -->

@extends('layouts.app') <!-- Assuming you have a layout file, adjust accordingly -->
@php
use App\Models\Editor;
use Illuminate\Http\Request;
$valorCodificado = request()->cookie('Editor');

$user=explode('+',base64_decode($valorCodificado));

@endphp

@section('content')
    <div class="container">
        <form method="POST" action="{{route('insertContent')}}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="theme" class="form-label">Theme</label>
                <input type="text" class="form-control" id="theme" name="theme">
            </div>

            <div class="mb-3">
                <label for="keyword" class="form-label">Keyword</label>
                <input type="text" class="form-control" id="keyword" name="keyword">
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control" id="category" name="category">
            </div>

            <div class="mb-3">
                <label for="anchor_1" class="form-label">Anchor 1</label>
                <input type="text" class="form-control" id="anchor_1" name="anchor_1">
            </div>

            <div class="mb-3">
                <label for="url_link_2" class="form-label">URL Link 1</label>
                <input type="text" class="form-control" id="url_link_2" name="url_link_1" >
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="do_follow_link_1" name="do_follow_link_1">
                <label class="form-check-label" for="do_follow">Do Follow Link 1</label>
            </div>

            <div class="mb-3">
                <label for="anchor_2" class="form-label">Anchor 2</label>
                <input type="text" class="form-control" id="anchor_2" name="anchor_2">
            </div>

            <div class="mb-3">
                <label for="url_link_2" class="form-label">URL Link 2</label>
                <input type="text" class="form-control" id="url_link_2" name="url_link_2" >
            </div>

            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="do_follow_link_2" name="do_follow_link_2">
                <label class="form-check-label" for="do_follow">Do Follow Link 2</label>
            </div>

            <div class="mb-3">
                <label for="anchor_3" class="form-label">Anchor 3</label>
                <input type="text" class="form-control" id="anchor_3" name="anchor_3">
            </div>

            <div class="mb-3">
                <label for="url_link_2" class="form-label">URL Link 3</label>
                <input type="text" class="form-control" id="url_link_2" name="url_link_3" >
            </div>

            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="do_follow_link_3" name="do_follow_link_3">
                <label class="form-check-label" for="do_follow">Do Follow Link 3</label>
            </div>

            <div class="mb-3">
                <label for="anchor_3" class="form-label">Image URL</label>
                <input type="text" class="form-control" id="image_url" name="image_url">
            </div>

            <div class="mb-3 d-flex flex-row">
                <div class="child flex-grow-1">
                    <label for="anchor_3" class="form-label">GoogleDrive URL</label>
                    <input type="text" class="form-control" id="Gdrive_url" name="gdrive_url">
                </div>
                <div class="child flex-grow-1">
                    <label for="anchor_3" class="form-label">Folder ID</label>
                    <input type="text" class="form-control" id="folder_id" name="folder_id">
                </div>
            </div>

          <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="insert_image" id="upload_radio" value="upload">
                    <label class="form-check-label" for="upload_radio">
                        Use Featured image
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label for="post_image" class="form-label">Post Image</label>
                <input type="file" class="form-control" id="post_image" name="post_image" required>
            </div>

            <label for="schedule">Schedule</label>
            <input type="date" name="schedule" id="schedule">
            
            <input type="hidden" name="session_user" value="{{$user[0]}}">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
