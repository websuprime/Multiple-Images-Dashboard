@extends('layouts.app')

@section('title', 'Images')

@section('content')
<div class="container">
    <a href="{{ route('images.create') }}" class="btn btn-success mb-3">+ Create New</a>

    @if($images->count())
    <div class="mb-3">
        <button id="selectAllBtn" class="btn btn-outline-primary btn-sm">Select All</button>
        <button id="deleteSelectedBtn" class="btn btn-danger btn-sm" style="display: none;">Delete Selected</button>
    </div>

    <form id="bulkDeleteForm">
        @csrf
        <table class="table table-bordered" id="imageTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="masterCheckbox"></th>
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
                    <td><input type="checkbox" class="imageCheckbox" value="{{ $image->id }}"></td>
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
    </form>

    <div class="d-flex justify-content-center mt-4">
        {{ $images->links('pagination::bootstrap-4') }}
    </div>
    @else
    <div class="alert alert-info">No images found.</div>
    @endif
</div>

<script>
    function copyToClipboard(text, btn) {
        event.preventDefault();
        navigator.clipboard.writeText(text).then(() => {
            btn.innerText = "URL copied!";
            setTimeout(() => {
                btn.innerText = "Copy URL";
            }, 1500);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const masterCheckbox = document.getElementById('masterCheckbox');
        const checkboxes = document.querySelectorAll('.imageCheckbox');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        const selectAllBtn = document.getElementById('selectAllBtn');

        function toggleDeleteButton() {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            deleteSelectedBtn.style.display = anyChecked ? 'inline-block' : 'none';
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', toggleDeleteButton);
        });

        selectAllBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            checkboxes.forEach(cb => cb.checked = true);
            toggleDeleteButton();
        });

        masterCheckbox?.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleDeleteButton();
        });

        deleteSelectedBtn?.addEventListener('click', function(e) {
            e.preventDefault();

            if (!confirm('Are you sure you want to delete selected images?')) return;

            const ids = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            fetch("{{ route('images.bulkDelete') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ids
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Failed to delete');
                    return response.json();
                })
                .then(() => {
                    ids.forEach(id => {
                        const row = document.getElementById('image-row-' + id);
                        if (row) row.remove();
                    });
                    toggleDeleteButton();
                })
                .catch(error => {
                    alert('Error deleting selected images.');
                    console.error(error);
                });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
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
                    .then(() => {
                        const row = document.getElementById('image-row-' + imageId);
                        if (row) row.remove();
                        toggleDeleteButton();
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
