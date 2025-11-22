<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'url',
        'is_active',
        'display_order',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Scope para obtener solo banners activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para obtener banners vigentes (dentro del rango de fechas)
     */
    public function scopeValid($query)
    {
        $now = Carbon::now()->format('Y-m-d');
        
        return $query->where(function($q) use ($now) {
            $q->where(function($subQ) use ($now) {
                // Si tiene start_date, debe ser menor o igual a hoy
                $subQ->whereNull('start_date')
                     ->orWhere('start_date', '<=', $now);
            })
            ->where(function($subQ) use ($now) {
                // Si tiene end_date, debe ser mayor o igual a hoy
                $subQ->whereNull('end_date')
                     ->orWhere('end_date', '>=', $now);
            });
        });
    }

    /**
     * Scope para ordenar por display_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    /**
     * Verificar si el banner está vigente
     */
    public function isValid(): bool
    {
        $now = Carbon::now();
        
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }
        
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }
        
        return true;
    }
}
