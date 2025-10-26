<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        return Media::latest()->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|file|max:10240', // 10MB
            'title' => 'nullable|string|max:255',
            'alt' => 'nullable|string|max:255',
            'license' => 'nullable|string|max:255',
            'attribution' => 'nullable|string|max:255',
        ]);

        $path = $request->file('file')->store('uploads', 'public');

        $media = Media::create([
            'title' => $data['title'] ?? null,
            'path'  => $path,
            'url'   => Storage::disk('public')->url($path),
            'alt'   => $data['alt'] ?? null,
            'license' => $data['license'] ?? null,
            'attribution' => $data['attribution'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        return response()->json($media, 201);
    }
}
