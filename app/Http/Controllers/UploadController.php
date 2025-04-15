<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Zxing\QrReader;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $file = $request->file('image');
        if ($file) {
            $path = $file->store('public/uploads');
            $localPath = storage_path('app/' . $path);

            // Scan barcode

            $localPath = storage_path('app/' . $path);

            $command = escapeshellcmd("python3 " . base_path('barcode_scanner/barcode_reader.py') . " " . $localPath);
            $output = shell_exec($command);
            $barcode = trim($output);

            if ($barcode === 'false') {
                return response()->json(['status' => 'error', 'message' => 'Barcode not found']);
            }

            return response()->json([
                'status' => 'success',
                'barcode' => $barcode,
                'path' => Storage::url($path)
            ]);

            return response()->json([
                'status' => 'success',
                'barcode' => $barcode,
                'path' => Storage::url($path)
            ]);
        }
        return response()->json(['status' => 'error'], 400);
    }


    public function store_old(Request $request)
    {
        $file = $request->file('image');
        $barcode = $request->input('barcode');

        if ($file) {
            $path = $file->store('public/uploads');
            $url = Storage::url($path);
            return response()->json([
                'status' => 'success',
                'path' => $url,
                'barcode' => $barcode
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'File missing'], 400);
    }


}
