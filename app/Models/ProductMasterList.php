<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laraditz\ModelFilter\Filterable;

class ProductMasterList extends Model
{
    use Filterable;

    protected $fillable = [
        'product_id',
        'type',
        'brand',
        'model',
        'capacity',
        'quantity',
    ];
}
