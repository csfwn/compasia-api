<?php

namespace App\Filters;

use Laraditz\ModelFilter\Filter;

class ProductMasterListFilter extends Filter
{
    public function productId(int $value)
    {
        $this->where('product_id', $value);
    }
}
