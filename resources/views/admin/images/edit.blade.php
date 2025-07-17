<form action="{{ route('images.update', $image->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Title</label>
    <input type="text" name="title" value="{{ $image->title }}">

    <label>Description</label>
    <textarea name="description">{{ $image->description }}</textarea>

    <button type="submit">Update</button>
</form>
