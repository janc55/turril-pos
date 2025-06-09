<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'price',
        'cost',
        'type',
        'image',
        'active',
        'stock_management',
        'is_combo', // Nuevo
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'active' => 'boolean',
        'stock_management' => 'boolean',
        'is_combo' => 'boolean', // Nuevo
    ];

    public function recipes()
    {
        // Un producto (sandwich) tiene una o más recetas
        return $this->hasMany(Recipe::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function currentStock()
    {
        return $this->hasMany(CurrentStock::class);
    }

    public function comboItems()
    {
        // Si este producto es un combo, tiene items de combo
        return $this->hasMany(ComboItem::class, 'combo_product_id');
    }

    public function partOfCombos()
    {
        // Si este producto es parte de un combo, está en combo_items
        return $this->hasMany(ComboItem::class, 'product_id');
    }
}
