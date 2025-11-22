<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImport extends Model
{
    protected $fillable = [
        'user_id',
        'filename',
        'original_filename',
        'status',
        'error_message',
        'products_imported',
        'products_skipped',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que realizó la importación
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener la ruta completa del archivo
     */
    public function getFilePathAttribute(): string
    {
        return Storage::path('imports/' . $this->filename);
    }

    /**
     * Obtener la URL de descarga del archivo
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('admin.imports.download', $this->id);
    }

    /**
     * Verificar si la importación fue exitosa
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Verificar si la importación tiene errores
     */
    public function hasError(): bool
    {
        return $this->status === 'error';
    }

    /**
     * Scope para filtrar importaciones por rango de fechas
     */
    public function scopeOlderThan($query, $days)
    {
        return $query->where('created_at', '<', now()->subDays($days));
    }

    /**
     * Eliminar el archivo físico del storage
     */
    public function deleteFile(): bool
    {
        if (Storage::exists('imports/' . $this->filename)) {
            return Storage::delete('imports/' . $this->filename);
        }
        return true;
    }
}
