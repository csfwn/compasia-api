<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Laraditz\ModelFilter\Filter;

class ProductMasterListFilter extends Filter
{
    public function search(?string $value)
    {
        if (!$value) return;
        
        $this->where(function (Builder $query) use ($value) {
            $query->where('product_id', 'LIKE', "%$value%")
                ->orWhere('type', 'LIKE', "%$value%")
                ->orWhere('brand', 'LIKE', "%$value%")
                ->orWhere('model', 'LIKE', "%$value%")
                ->orWhere('capacity', 'LIKE', "%$value%");
        });
    }

    public function productId(int $value)
    {
        $this->where('product_id', $value);
    }
}
