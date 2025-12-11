@extends('layouts.app')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">All Posts</h3>
                <a href="{{ route('posts.create') }}" class="btn btn-primary px-4">
                    <i class="bi bi-plus-circle me-1"></i> Create Post
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th width="5%">ID</th>
                        <th width="12%">Image</th>
                        <th width="20%">Name</th>
                        <th>Description</th>
                        <th width="12%" class="text-center">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td class="fw-semibold">{{ $post->id }}</td>

                            <td>
                                @if($post->image)
                                    <img src="{{ Storage::url($post->image) }}"
                                         class="img-thumbnail rounded"
                                         style="width: 70px; height: 70px; object-fit: cover;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>

                            <td>{{ $post->name }}</td>
                            <td>{{ Str::limit($post->description, 80) }}</td>

                            <td class="text-center">

                                <!-- View -->
                                <a href="{{ route('posts.show', $post) }}"
                                   class="btn btn-sm btn-light border rounded-circle me-1"
                                   data-bs-toggle="tooltip"
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('posts.edit', $post) }}"
                                   class="btn btn-sm btn-light border rounded-circle me-1"
                                   data-bs-toggle="tooltip"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('posts.destroy', $post) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this post?');">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-light border rounded-circle"
                                            data-bs-toggle="tooltip"
                                            title="Delete">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-file-earmark"></i> No posts found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $posts->links() }}
            </div>

        </div>
    </div>

    <!-- Enable Bootstrap Tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>

@endsection
