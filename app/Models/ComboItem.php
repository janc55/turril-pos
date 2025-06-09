<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'combo_product_id',
        'product_id',
        'quantity',
        'min_choices',
        'max_choices',
        'is_customizable',
    ];

    protected $casts = [
        'is_customizable' => 'boolean',
        'quantity' => 'integer',
        'min_choices' => 'integer',
        'max_choices' => 'integer',
    ];

    public function comboProduct()
    {
        return $this->belongsTo(Product::class, 'combo_product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
