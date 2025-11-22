<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'plu',
        'description',
        'price',
        'type',

        'image',
        'is_available',
        'is_featured',
        'min_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean'
    ];

    /**
     * Obtener el tipo de producto legible
     */
    public function getTypeNameAttribute(): string
    {
        return $this->type === 'P' ? 'Por Kilo' : 'Por Unidad';
    }

    /**
     * Obtener el sufijo de precio según el tipo
     */
    public function getPriceSuffixAttribute(): string
    {
        return $this->type === 'P' ? '/kg' : '/unidad';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Ofertas asociadas a este producto
     */
    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_product')
                    ->withTimestamps();
    }

    /**
     * Obtener oferta activa para este producto
     */
    public function getActiveOffer()
    {
        return $this->offers()
                    ->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->first();
    }
}
