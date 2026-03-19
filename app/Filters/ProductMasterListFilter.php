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

    public function sort(?string $value)
    {
        if (!$value) return;

        [$column, $direction] = array_pad(explode('|', $value), 2, null);

        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        $allowed = [
            'id',
            'product_id',
            'type',
            'brand',
            'model',
            'capacity',
            'quantity',
        ];

        if (!in_array($column, $allowed)) return;

        $this->orderBy($column, $direction);
    }
}
