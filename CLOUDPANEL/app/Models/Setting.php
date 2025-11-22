<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Obtener el valor de una configuración
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        // Convertir según el tipo
        return match($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'number' => (int) $setting->value,
            default => $setting->value,
        };
    }

    /**
     * Establecer o actualizar una configuración
     */
    public static function set($key, $value, $type = 'text')
    {
        // Convertir booleanos a string '0' o '1'
        if ($type === 'boolean') {
            $value = $value ? '1' : '0';
        }
        
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }
}