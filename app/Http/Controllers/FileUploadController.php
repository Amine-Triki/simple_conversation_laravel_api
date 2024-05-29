<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Models\File;

class FileUploadController extends Controller
{
    public function uploadImage(Request $request)
    {

        try {

            $request->validate([
                'files.*' => 'required|mimes:png,jpg,jpeg|max:2048', // extension png,jpg,jpeg
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        }

        $paths = [];
        foreach ($request->file('files') as $file) {
            $path = $file->store('images');
            $paths[] = $path;

            $newFile = new File();
            $newFile->path = $path;
            $newFile->type = 'image';
            $newFile->save();
        }

        return response()->json(['message' => 'Image uploaded successfully', 'path' => $paths], 200);
    }



    public function uploadPDF(Request $request)
    {
        try {
        $request->validate([
            'files.*' => 'required|mimes:pdf|max:20480', //extension pdf
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation Error',
            'errors' => $e->errors()
        ], 422); // 422 Unprocessable Entity
    }

        $paths = [];
        foreach ($request->file('files') as $file) {
        $path = $file->store('pdfs');
        $paths[] = $path;

        $newFile = new File();
        $newFile->path = $path;
        $newFile->type = 'pdf';
        $newFile->save();
        }
        return response()->json(['message' => 'PDF uploaded successfully', 'path' => $paths], 200);
    }



    public function uploadAny(Request $request)
    {

        try {
        $request->validate([
            'files.*' => 'required|max:20480', // file size KB
        ]);


    }catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation Error',
            'errors' => $e->errors()
        ], 422); // 422 Unprocessable Entity
    }

        $paths = [];
        foreach ($request->file('files') as $file) {
        $path = $file->store('any_files');
        $paths[] = $path;

        $newFile = new File();
        $newFile->path = $path;
        $newFile->type = 'any';
        $newFile->save();
        }

        return response()->json(['message' => 'File uploaded successfully', 'path' => $paths], 200);
    }



    // delete files uploaded
    public function deleteFile($id)
{
    // Find the file in the database
    $file = File::find($id);

    // Check if the file exists
    if (!$file) {
        return response()->json(['message' => 'File not found'], 404);
    }

    // Delete the file from storage
    if (Storage::exists($file->path)) {
        Storage::delete($file->path);
    }

    // Delete the file from the database
    $file->delete();

    return response()->json(['message' => 'File deleted successfully'], 200);
}



}
