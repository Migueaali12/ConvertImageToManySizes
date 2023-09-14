<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');

            $extension = $image->getClientOriginalExtension();
            $allowedExtensions = ['png', 'jpg', 'jpeg'];

            if (!in_array($extension, $allowedExtensions)) {
                File::delete($image->getPathname());
                return response()->json(['error' => 'El formato del archivo no es compatible: ' . $image->getClientOriginalName()]);
            }

            $imageName = $image->getClientOriginalName();
            $imageId = uniqid() . '.' . $extension;
            $image->move(public_path('uploadsImg'), $imageId);

            return response()->json(['success' => true, 'imageName' => $imageName, 'imagePath' => asset('uploadsImg/' . $imageId)]);
        }

        return response()->json(['error' => 'Ocurrió un error al subir la imagen o el archivo no es válido.']);
    }
}
