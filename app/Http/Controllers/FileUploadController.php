<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Zxing\QrReader;

class FileUploadController extends Controller
{

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        $user = Auth::user();
        $path = $request->file('image')->store('uploads');

        // Barcode reading logic here (you can call JS front-end scanner or use shell command with zxing)

        $barcodeValue = $request->input('barcode'); // coming from frontend or scan lib

        UploadedFile::create([
            'user_id' => $user->id,
            'filename' => $path,
            'barcode' => $barcodeValue,
        ]);

        return response()->json(['message' => 'File uploaded successfully', 'barcode' => $barcodeValue]);
    }

    public function showForm()
    {
        return view('barcode-upload');
    }


    public function readBarcode(Request $request)
    {
        $request->validate([
            'barcode_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // Save the uploaded file temporarily
        $imagePath = $request->file('barcode_image')->store('barcodes', 'public');

        // Get the full path
        $fullPath = storage_path('app/public/' . $imagePath);

        // Read the barcode
        $qrcode = new QrReader($fullPath); // This works for QR and barcodes
        // dd($qrcode);
        $text = $qrcode->text(); // This will return the decoded text

        if ($text) {
            return response()->json(['data' => $text]);
        } else {
            return response()->json(['error' => 'Unable to detect barcode.'], 400);
        }
    }


}
