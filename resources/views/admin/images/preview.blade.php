@extends('layouts.app') {{-- Adjust to your layout --}}
@section('content')

<div class="container">
    <h2 class="mb-4">Image Preview</h2>

    <div class="card" style="width: 30rem;">
        <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" alt="preview">
        <div class="card-body">
            <h5 class="card-title">{{ $image->title ?? 'No title' }}</h5>
            <p class="card-text">{{ $image->description ?? 'No description' }}</p>
            <button class="btn btn-info" onclick="copyToClipboard('{{ asset('storage/' . $image->image_path) }}', this)">
                Copy URL <i class="fa fa-copy"></i>
            </button>
        </div>
    </div>

    <a href="{{ route('images.index') }}" class="btn btn-secondary mt-3">Back to List</a>
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
</script>

@endsection
