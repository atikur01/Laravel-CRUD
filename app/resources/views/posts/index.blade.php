@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>All Posts</h2>
        <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Post</a>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>
                    @if($post->image)
                        <img src="{{ Storage::url($post->image) }}" alt="" style="width:80px; height:80px;">
                    @endif
                </td>
                <td>{{ $post->name }}</td>
                <td>{{ Str::limit($post->description, 80) }}</td>
                <td>
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No posts yet.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $posts->links() }}
@endsection
