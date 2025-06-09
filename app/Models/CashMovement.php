<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_box_id',
        'user_id',
        'type',
        'amount',
        'description',
        'related_sale_id',
        'related_purchase_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function cashBox()
    {
        return $this->belongsTo(CashBox::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function relatedSale()
    {
        return $this->belongsTo(Sale::class, 'related_sale_id');
    }

    public function relatedPurchase()
    {
        return $this->belongsTo(Purchase::class, 'related_purchase_id');
    }
}
