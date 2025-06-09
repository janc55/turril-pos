<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    use HasFactory;

    protected $table = 'recipe_ingredients'; // Nombre de tu tabla pivote

    // No necesitas definir $primaryKey si es 'id' por defecto
    // protected $primaryKey = ['recipe_id', 'ingredient_id']; // **Elimina esta línea si existe**

    public $incrementing = true; // Asegura que el ID se auto-incremente
    protected $keyType = 'int'; // Asegura que el tipo de clave es entero

    protected $fillable = ['recipe_id', 'ingredient_id', 'quantity'];

    protected $casts = [
        'quantity' => 'decimal:4',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
