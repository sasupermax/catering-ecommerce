<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeliveryDateController extends Controller
{
    public function availableDates()
    {
        // Obtener configuración
        $minDaysAdvance = (int) Setting::where('key', 'min_days_advance')->value('value') ?? 2;
        $maxDaysAdvance = (int) Setting::where('key', 'max_days_advance')->value('value') ?? 30;
        $maxOrdersPerDay = (int) Setting::where('key', 'max_orders_per_day')->value('value') ?? 10;

        // Calcular fechas mínima y máxima
        $minDate = Carbon::now()->addDays($minDaysAdvance);
        $maxDate = Carbon::now()->addDays($maxDaysAdvance);

        // Obtener conteo de pedidos por fecha en el rango (solo órdenes con pago aprobado)
        $occupiedDates = Order::selectRaw('delivery_date, COUNT(*) as count')
            ->whereBetween('delivery_date', [$minDate->format('Y-m-d'), $maxDate->format('Y-m-d')])
            ->where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'confirmed'])
            ->groupBy('delivery_date')
            ->pluck('count', 'delivery_date')
            ->toArray();

        return response()->json([
            'minDate' => $minDate->format('Y-m-d'),
            'maxDate' => $maxDate->format('Y-m-d'),
            'maxOrdersPerDay' => $maxOrdersPerDay,
            'occupiedDates' => $occupiedDates
        ]);
    }
}
