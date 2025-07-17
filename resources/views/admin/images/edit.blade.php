<form action="{{ route('images.update', $image->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div>
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="{{ old('title', $image->title) }}">
        @error('title')
        <div style="color:red;">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="description">Description</label>
        <textarea name="description" id="description">{{ old('description', $image->description) }}</textarea>
        @error('description')
        <div style="color:red;">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>Current Image:</label><br>
        <img id="currentImage" src="{{ asset('storage/' . $image->image_path) }}" style="max-width: 200px; border:1px solid #ccc; padding:5px; margin-bottom: 10px;"><br><br>

        <label for="image">Replace Image</label><br>
        <input type="file" name="image" id="image" accept="image/*" onchange="previewNewImage(event)">
        @error('image')
        <div style="color:red;">{{ $message }}</div>
        @enderror

        <div style="margin-top: 10px;">
            <strong>New Image Preview:</strong><br>
            <img id="newImagePreview" style="max-width: 200px; display: none; border:1px solid #ccc; padding:5px;">
        </div>
    </div>

    <button type="submit" style="margin-top: 20px;">Update</button>
</form>

<script>
    function previewNewImage(event) {
        const output = document.getElementById('newImagePreview');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.style.display = 'block';
    }
</script>
