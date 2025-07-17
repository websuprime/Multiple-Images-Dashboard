@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->any())
    <div style="color:red; margin-bottom:15px;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="images[]" multiple required id="imageInput">

        <div id="preview" style="display:flex; gap:10px; flex-wrap: wrap; margin-top: 10px;"></div>

        <button type="submit" style="margin-top: 15px;">Upload</button>
    </form>
</div>

<script>
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('preview');

    input.addEventListener('change', function() {
        preview.innerHTML = ''; // Clear old previews

        Array.from(this.files).forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = e => {
                const wrapper = document.createElement('div');
                wrapper.style.position = 'relative';
                wrapper.style.display = 'inline-block';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.border = '1px solid #ccc';
                img.style.borderRadius = '4px';
                wrapper.appendChild(img);

                preview.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection
