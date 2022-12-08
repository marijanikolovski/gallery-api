<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateGalleryRequest;
use App\Http\Requests\EditGalleryRequest;

class GalleriesController extends Controller
{
    public function index()
    {
        $query = Gallery::with('comments', 'user', 'images');
        $galleries = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json($galleries);
    }

    public function show($id)
    {
        $gallery = Gallery::with(['images', 'user', 'comments', 'comments.user'])->find($id);
        return response()->json($gallery);
    }

    public function store(CreateGalleryRequest $request)
    {
        $validated = $request->validated();

        $gallry = Gallery::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description']
        ]);

        $images = $request->get('images', []);
        foreach ($images as $image) {
            Image::create([
                'gallery_id' => $gallry->id,
                'url' => $image['url']
            ]);
        }
        $gallry->load('images', 'user', 'comments', 'comments.user');

        return response()->json($gallry, 201);
    }

    public function update($id, EditGalleryRequest $request)
    {
        $validated = $request->validated();

        $gallery = Gallery::findOrFail($id);
        $gallery->update($validated);

        $images = $request->get('images', []);
        foreach ($images as $image) {
            $imagesArr[] = Image::create([
                'gallery_id' => $gallery->id,
                'url' => $image['url']
            ]);
        }
        $gallery->load('images', 'user', 'comments', 'comments.user');
        return response()->json($gallery);
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();
        return response()->noContent();
    }
}
