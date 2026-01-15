<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AllImageController extends Controller
{
    public function index()
    {
        $images = Image::latest()->get();
        return view('images.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $created = [];
        foreach ($request->file('images') as $img) {
            $name = uniqid() . '.' . $img->extension();
            $path = 'uploads/' . $name;
            $img->move(public_path('uploads'), $name);

            $image = Image::create([
                'filename' => $name,
                'path' => $path
            ]);
            $created[] = $image;
        }

        return response()->json([
            'success' => true,
            'message' => 'Images uploaded successfully!',
            'images' => $created
        ]);
    }
    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        File::delete(public_path('uploads/' . $image->image));
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully!'
        ]);
    }
}
