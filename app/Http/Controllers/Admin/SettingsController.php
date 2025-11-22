<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'site_phone' => 'required|string|max:50',
            'site_address' => 'required|string|max:500',
            'max_orders_per_day' => 'required|integer|min:1|max:100',
            'min_days_advance' => 'required|integer|min:0|max:30',
            'max_days_advance' => 'required|integer|min:1|max:90',
            'minimum_order_amount' => 'required|numeric|min:0',
            'carousel_transition_duration' => 'required|integer|min:1000|max:30000',
            'maintenance_mode' => 'boolean',
            'payment_mercadopago_enabled' => 'boolean',
            'payment_bank_transfer_enabled' => 'boolean',
            'payment_cash_enabled' => 'boolean',
            'mercadopago_public_key' => 'nullable|string|max:255',
            'mercadopago_access_token' => 'nullable|string|max:255',
        ]);

        // Validar que min_days_advance sea menor que max_days_advance
        if ($validated['min_days_advance'] >= $validated['max_days_advance']) {
            return back()->withErrors(['min_days_advance' => 'Los días mínimos deben ser menores que los días máximos.']);
        }

        // Agregar explícitamente los checkboxes que no están marcados (no se envían en el request)
        $booleanFields = ['maintenance_mode', 'payment_mercadopago_enabled', 'payment_bank_transfer_enabled', 'payment_cash_enabled'];
        foreach ($booleanFields as $field) {
            if (!isset($validated[$field])) {
                $validated[$field] = false;
            }
        }

        foreach ($validated as $key => $value) {
            $type = match($key) {
                'maintenance_mode', 'payment_mercadopago_enabled', 'payment_bank_transfer_enabled', 'payment_cash_enabled' => 'boolean',
                'max_orders_per_day', 'min_days_advance', 'max_days_advance' => 'number',
                'minimum_order_amount' => 'number',
                'site_address' => 'textarea',
                default => 'text',
            };

            Setting::set($key, $value ?? '', $type);
        }

        return back()->with('success', 'Configuración actualizada correctamente');
    }
}
