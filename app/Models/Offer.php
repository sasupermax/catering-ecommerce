<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'is_active',
        'min_purchase_amount',
        'image',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
    ];

    /**
     * Productos asociados a esta oferta
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_product')
                    ->withTimestamps();
    }

    /**
     * Verificar si la oferta está vigente (sin validar monto mínimo)
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now()->startOfDay();
        return $now->between($this->start_date, $this->end_date);
    }

    /**
     * Verificar si la oferta es aplicable dado un total de compra
     */
    public function isApplicable($totalAmount)
    {
        if (!$this->isValid()) {
            return false;
        }

        // Si hay monto mínimo, validar que se cumpla
        if ($this->min_purchase_amount > 0) {
            return $totalAmount >= $this->min_purchase_amount;
        }

        return true;
    }

    /**
     * Calcular el descuento para un monto
     */
    public function calculateDiscount($amount)
    {
        if ($this->discount_type === 'percentage') {
            return $amount * ($this->discount_value / 100);
        }

        return min($this->discount_value, $amount);
    }

    /**
     * Scope para ofertas activas y vigentes
     */
    public function scopeActive($query)
    {
        $now = Carbon::now()->startOfDay();
        return $query->where('is_active', true)
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }
}
