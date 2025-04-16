<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ProcessBarcodeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'command:name';
    protected $signature = 'barcode:process-images';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';    
    protected $description = 'Process and upload barcode images from a folder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $directory = '/var/www/html/samir/scan_uploads/barcode_images';
        $files = glob($directory . '/*.{jpg,jpeg,png}', GLOB_BRACE);

        if (empty($files)) {
            $this->info('No images found.');
            return;
        }

        foreach ($files as $file) {
            $filename = basename($file);
            $storagePath = 'public/uploads/' . $filename;

            // Move file to Laravel storage
            Storage::put($storagePath, file_get_contents($file));
            $localPath = storage_path('app/' . $storagePath);

            // Scan barcode
            $command = escapeshellcmd("python3 " . base_path('barcode_scanner/barcode_reader.py') . " " . $localPath);
            $output = shell_exec($command);
            $barcode = trim($output);

            if ($barcode === 'false' || empty($barcode)) {
                // Move file to lostnfound
                $lostPath = 'public/lostnfound/' . $filename;
                Storage::put($lostPath, file_get_contents($file));
                Storage::delete($storagePath); // optional: delete from uploads
                $this->error("❌ Barcode not found. Moved to lostnfound: $filename");
                continue;                
            }
            
            $this->info("✅ Scanned [$barcode] from $filename");

            // After processing, you can move the file to a /processed folder so they don’t get scanned again.
            // Optional: move file to processed folder (not required if using Storage)
            rename($file, $directory . '/processed/' . $filename);
        }

        
        $this->info('All images processed.');
    }
}
