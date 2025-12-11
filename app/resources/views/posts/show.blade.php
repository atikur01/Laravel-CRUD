@extends('layouts.app')

@section('content')
    <h2>View Post</h2>

    <div class="card">
        <div class="card-body">
            <h4>{{ $post->name }}</h4>
            @if($post->image)
                <img src="{{ Storage::url($post->image) }}" style="max-width:300px; height:auto;" class="mb-3">
            @endif

            <p>{{ $post->description }}</p>

            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@endsection
