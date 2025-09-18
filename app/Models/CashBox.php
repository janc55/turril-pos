<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBox extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'description',
        'initial_balance',
        'current_balance',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'initial_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function cashMovements()
    {
        return $this->hasMany(CashMovement::class);
    }
}
