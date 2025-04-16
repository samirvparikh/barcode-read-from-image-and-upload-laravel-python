<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeReader;
use Illuminate\Support\Facades\Storage;

class BarcodeUploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $image = $request->file('image');
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads'), $filename);

        // Process barcode reading (Example)
        $barcodeReader = new BarcodeReader();
        $barcode = $barcodeReader->readBarcode(public_path('uploads/' . $filename));

        // Log barcode data
        \Log::info('Uploaded File: ' . $filename . ' - Barcode: ' . ($barcode ?? 'Not found'));

        return back()->with('success', 'File uploaded successfully. Barcode: ' . ($barcode ?? 'Not detected'));
    }
}
