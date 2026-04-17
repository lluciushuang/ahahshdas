<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    public function product_category():BelongsTo{
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
