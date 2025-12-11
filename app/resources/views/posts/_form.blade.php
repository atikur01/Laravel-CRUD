<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $post->name ?? '') }}">
    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $post->description ?? '') }}</textarea>
    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Image</label>
    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
    @error('image')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if(!empty($post->image))
        <div class="mt-2">
            <img src="{{ Storage::url($post->image) }}" style="width:120px; height:auto;">
        </div>
    @endif
</div>
