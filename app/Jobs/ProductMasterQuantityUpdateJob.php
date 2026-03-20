<?php

namespace App\Jobs;

use App\Imports\ProductMasterQuantityUpdateImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductMasterQuantityUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        protected string $filePath
    ) {}

    public function handle(): void
    {
        if (!Storage::exists($this->filePath)) {
            Log::error("File not found: {$this->filePath}");
            return;
        }

        try {
            Excel::import(
                new ProductMasterQuantityUpdateImport,
                Storage::path($this->filePath)
            );

            Storage::delete($this->filePath);

        } catch (\Throwable $e) {
            Log::error('Import failed: ' . $e->getMessage());
            
            $this->fail($e);
        }
    }
}