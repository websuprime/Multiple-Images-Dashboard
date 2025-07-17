<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::latest()->paginate(10); // show 10 per page
        return view('admin.images.index', compact('images'));
    }


    public function create()
    {
        return view('admin.images.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('uploads', 'public');

            Image::create([
                'image_path' => $path,
            ]);
        }

        return redirect()->route('images.index')->with('success', 'Images uploaded successfully!');
    }


    public function edit($id)
    {
        $image = Image::findOrFail($id);
        return view('admin.images.edit', compact('image'));
    }

    public function update(Request $request, $id)
    {
        $image = Image::findOrFail($id);
        $image->update($request->only(['title', 'description']));

        return redirect()->route('images.index')->with('success', 'Image updated!');
    }

    public function preview($id)
    {
        $image = Image::findOrFail($id);
        return view('admin.images.preview', compact('image'));
    }

    public function destroy($id)
    {
        // Find the image by its ID
        $image = Image::findOrFail($id);

        // Check if the image file exists in storage
        if (Storage::exists($image->image_path)) {
            // Delete the file from storage
            Storage::delete($image->image_path);
        }

        // Delete the image record from the database
        $image->delete();

        // Return a success response
        return response()->json(['message' => 'Image deleted successfully']);
    }
}
