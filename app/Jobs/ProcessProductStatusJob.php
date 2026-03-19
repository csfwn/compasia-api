<?php

namespace App\Jobs;

use App\Models\ProductMasterList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\InteractsWithQueue;
class ProcessProductStatusJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue;

    public $tries = 1;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle(): void
    {
        DB::beginTransaction();

        try {
            if (!Storage::exists($this->filePath)) {
                \Log::error('File not found: ' . $this->filePath);
                return;
            }

            $fullPath = Storage::path($this->filePath);

            $rows = Excel::toArray([], $fullPath)[0] ?? [];

            if (count($rows) <= 1) return;

            $header = $rows[0];

            $productIdIndex = array_search('Product ID', $header);
            $statusIndex = array_search('Status', $header);

            if ($productIdIndex === false || $statusIndex === false) {
                \Log::error('Invalid Excel format: Missing required columns');
                return;
            }

            unset($rows[0]);

            $productIds = collect($rows)
                ->pluck($productIdIndex)
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            $products = ProductMasterList::whereIn('product_id', $productIds)
                ->get()
                ->keyBy('product_id');

            foreach ($rows as $row) {

                $productId = $row[$productIdIndex] ?? null;
                $status = strtolower(trim($row[$statusIndex] ?? ''));
                $qty = 1;

                if (!$productId || !in_array($status, ['sold', 'buy'])) {
                    continue;
                }

                if (!isset($products[$productId])) {
                    continue;
                }

                $product = $products[$productId];

                if ($status === 'sold') {
                    $product->quantity = max(0, $product->quantity - $qty);
                } else {
                    $product->quantity += $qty;
                }
            }

            foreach ($products as $product) {
                $product->save();
            }

            DB::commit();

            Storage::delete($this->filePath);

        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error('Excel processing failed: ' . $e->getMessage());

            $this->fail($e);
        }
    }
}