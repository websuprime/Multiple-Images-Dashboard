@extends('layouts.app')

@section('title', 'Images')

@section('content')
<div class="container">
    <a href="{{ route('images.create') }}" class="btn btn-success mb-3">+ Create New</a>

    <table class="table table-bordered" id="imageTable">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Description</th>
                <th>URL</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($images as $index => $image)
            <tr id="image-row-{{ $image->id }}">
                <td>{{ $index + 1 }}</td>
                <td>
                    <img src="{{ asset('storage/' . $image->image_path) }}" width="50" height="50" alt="thumbnail">
                </td>
                <td>{{ $image->title ?? 'NA' }}</td>
                <td>{{ $image->description ?? 'NA' }}</td>
                <td>
                    <button class="btn btn-info btn-sm" onclick="copyToClipboard('{{ asset('storage/' . $image->image_path) }}', this)">
                        Copy URL <i class="fa fa-copy"></i>
                    </button>
                </td>
                <td>
                    <a href="{{ route('images.preview', $image->id) }}" class="btn btn-secondary btn-sm" title="Preview">
                        <i class="fa fa-eye"></i> Preview
                    </a>
                    <a href="{{ route('images.edit', $image->id) }}" class="btn btn-primary btn-sm" title="Edit">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                        data-id="{{ $image->id }}"
                        data-url="{{ route('images.destroy', $image->id) }}"
                        title="Delete">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $images->links('pagination::bootstrap-4') }}
    </div>
</div>

<script>
    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(function() {
            btn.innerText = "URL copied!";
            setTimeout(() => {
                btn.innerText = "Copy URL";
            }, 1500);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.delete-btn');

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                if (!confirm('Are you sure you want to delete this image?')) return;

                const imageId = btn.getAttribute('data-id');
                const url = btn.getAttribute('data-url');

                fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to delete');
                        return response.json();
                    })
                    .then(data => {
                        const row = document.getElementById('image-row-' + imageId);
                        row.innerHTML = `<td colspan="6" class="text-center text-success">Image deleted successfully.</td>`;
                    })
                    .catch(error => {
                        alert('Error deleting image.');
                        console.error(error);
                    });
            });
        });
    });
</script>
@endsection
