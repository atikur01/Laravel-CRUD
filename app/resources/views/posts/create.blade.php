@extends('layouts.app')

@section('content')
    <h2>Create Post</h2>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('posts._form')
        <button class="btn btn-success">Save</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
