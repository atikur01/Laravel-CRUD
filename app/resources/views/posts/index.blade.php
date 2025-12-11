@extends('layouts.app')

@section('title', 'All Posts')

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
                <table class="table table-hover align-middle" id="posts-table">
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
                        <tr data-id="{{ $post->id }}">
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

                                <!-- Delete (AJAX) -->
                                <button type="button"
                                        class="btn btn-sm btn-light border rounded-circle btn-delete"
                                        data-id="{{ $post->id }}"
                                        data-name="{{ $post->name }}"
                                        data-bs-toggle="tooltip"
                                        title="Delete">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>

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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Enable Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // SweetAlert2 Toast config
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Delete button click -> confirm -> AJAX delete
            document.querySelectorAll('.btn-delete').forEach(function(btn) {
                btn.addEventListener('click', function (e) {
                    const id = this.dataset.id;
                    const name = this.dataset.name || 'this item';
                    const row = document.querySelector(`tr[data-id="${id}"]`);

                    Swal.fire({
                        title: `Delete "${name}"?`,
                        text: "This action cannot be undone.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        focusCancel: true,
                        customClass: {
                            confirmButton: 'btn btn-danger me-2',
                            cancelButton: 'btn btn-secondary'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // show loading modal
                            Swal.fire({
                                title: 'Deleting...',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => Swal.showLoading(),
                                showConfirmButton: false
                            });

                            fetch("{{ url('/posts') }}/" + id, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                                .then(async (res) => {
                                    const data = await res.json().catch(()=> ({}));
                                    if (res.ok) {
                                        // remove the row with a fade out
                                        if (row) {
                                            row.style.transition = "opacity 300ms ease, height 300ms ease";
                                            row.style.opacity = 0;
                                            setTimeout(() => row.remove(), 320);
                                        }

                                        Swal.close();
                                        Toast.fire({
                                            icon: 'success',
                                            title: data.message || 'Deleted successfully'
                                        });
                                    } else {
                                        Swal.close();
                                        Toast.fire({
                                            icon: 'error',
                                            title: data.message || 'Failed to delete'
                                        });
                                    }
                                })
                                .catch((err) => {
                                    console.error(err);
                                    Swal.close();
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'An error occurred. Try again.'
                                    });
                                });
                        }
                    });

                });
            });

            // Show server-side flash messages (fallback when redirected)
            @if(session('success'))
            Toast.fire({ icon: 'success', title: {!! json_encode(session('success')) !!} });
            @endif

            @if(session('error'))
            Toast.fire({ icon: 'error', title: {!! json_encode(session('error')) !!} });
            @endif

        });
    </script>
@endpush
