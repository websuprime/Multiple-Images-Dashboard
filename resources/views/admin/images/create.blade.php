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

    <form id="uploadForm" action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" id="imageInput" name="images[]" multiple required>

        <div id="preview" style="display:flex; gap:10px; flex-wrap: wrap; margin-top: 10px;"></div>

        <button type="submit" style="margin-top: 15px;">Upload</button>
    </form>
</div>

<script>
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('preview');
    let fileList = [];

    input.addEventListener('change', function() {
        preview.innerHTML = '';
        fileList = Array.from(this.files);

        fileList.forEach((file, index) => {
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

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = '&times;';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '2px';
                removeBtn.style.right = '5px';
                removeBtn.style.color = '#fff';
                removeBtn.style.backgroundColor = 'rgba(0,0,0,0.6)';
                removeBtn.style.borderRadius = '50%';
                removeBtn.style.cursor = 'pointer';
                removeBtn.style.padding = '2px 6px';
                removeBtn.style.fontSize = '16px';
                removeBtn.title = 'Remove';

                removeBtn.addEventListener('click', () => {
                    fileList.splice(index, 1);
                    renderPreview();
                });

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                preview.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    });

    function renderPreview() {
        preview.innerHTML = '';

        fileList.forEach((file, index) => {
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

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = '&times;';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '2px';
                removeBtn.style.right = '5px';
                removeBtn.style.color = '#fff';
                removeBtn.style.backgroundColor = 'rgba(0,0,0,0.6)';
                removeBtn.style.borderRadius = '50%';
                removeBtn.style.cursor = 'pointer';
                removeBtn.style.padding = '2px 6px';
                removeBtn.style.fontSize = '16px';
                removeBtn.title = 'Remove';

                removeBtn.addEventListener('click', () => {
                    fileList.splice(index, 1);
                    renderPreview();
                });

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                preview.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });

        // Update the input field with the new fileList using DataTransfer
        const dataTransfer = new DataTransfer();
        fileList.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
    }
</script>
@endsection
