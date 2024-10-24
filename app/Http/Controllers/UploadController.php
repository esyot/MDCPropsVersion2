<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        // Extract base64 image data
        $imageData = $request->input('image-data');
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);

        // Generate a unique file name
        $uniqueId = \Str::random(10);
        $fileName = $uniqueId . '.png';

        // Decode base64 and save to storage
        $filePath = 'images/' . $fileName;
        Storage::disk('public')->put($filePath, base64_decode($imageData));

        // Return success response
        return back()->with('success', 'File uploaded successfully')->with('path', Storage::url($filePath));
    }
}
