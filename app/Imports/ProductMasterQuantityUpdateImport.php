<?php

namespace App\Imports;

use App\Models\ProductMasterList;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductMasterQuantityUpdateImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        if ($rows->count() <= 1) return;

        $header = $rows->first();
        $dataRows = $rows->skip(1);

        $productIdIndex = $header->search('Product ID');
        $statusIndex = $header->search('Status');

        if ($productIdIndex === false || $statusIndex === false) {
            Log::error('Invalid Excel format');
            return;
        }

        // get product IDs
        $productIds = $dataRows
            ->pluck($productIdIndex)
            ->filter()
            ->unique();

        $products = ProductMasterList::whereIn('product_id', $productIds)
            ->get()
            ->keyBy('product_id');

        foreach ($dataRows as $row) {
            $productId = $row[$productIdIndex] ?? null;
            $status = strtolower(trim($row[$statusIndex] ?? ''));

            if (!$productId || !isset($products[$productId])) continue;

            $product = $products[$productId];

            if ($status === 'sold') {
                $product->quantity = max(0, $product->quantity - 1);
            } elseif ($status === 'buy') {
                $product->quantity += 1;
            }
        }

        // save all
        foreach ($products as $product) {
            $product->save();
        }
    }
}