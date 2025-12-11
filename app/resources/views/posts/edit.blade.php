@extends('layouts.app')

@section('content')
    <h2>Edit Post</h2>

    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('posts._form', ['post' => $post])
        <button class="btn btn-success">Update</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
