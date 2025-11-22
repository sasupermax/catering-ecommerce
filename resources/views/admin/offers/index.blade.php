@extends('layouts.admin')

@section('title', 'Gestión de Ofertas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Ofertas</h1>
        <a href="{{ route('admin.offers.create') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
            ➕ Nueva Oferta
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Oferta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descuento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vigencia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Productos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($offers as $offer)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($offer->image)
                            <img src="{{ asset('storage/' . $offer->image) }}" alt="{{ $offer->name }}" class="w-12 h-12 rounded object-cover mr-3">
                            @else
                            <div class="w-12 h-12 rounded bg-orange-100 flex items-center justify-center mr-3">
                                <span class="text-2xl">🎁</span>
                            </div>
                            @endif
                            <div>
                                <div class="font-semibold text-gray-900">{{ $offer->name }}</div>
                                @if($offer->description)
                                <div class="text-sm text-gray-500">{{ Str::limit($offer->description, 50) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-lg font-bold text-orange-600">
                            @if($offer->discount_type === 'percentage')
                                {{ $offer->discount_value }}%
                            @else
                                ${{ number_format($offer->discount_value, 2) }}
                            @endif
                        </span>
                        <div class="text-xs text-gray-500">
                            {{ $offer->discount_type === 'percentage' ? 'Porcentaje' : 'Monto fijo' }}
                        </div>
                        @if($offer->min_purchase_amount > 0)
                        <div class="text-xs text-gray-500">
                            Mín: ${{ number_format($offer->min_purchase_amount, 2) }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div>{{ $offer->start_date->format('d/m/Y') }}</div>
                        <div class="text-gray-500">hasta</div>
                        <div>{{ $offer->end_date->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">{{ $offer->products->count() }} productos</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($offer->isValid())
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">✓ Activa</span>
                        @elseif($offer->is_active && $offer->start_date > now())
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">⏳ Próxima</span>
                        @elseif($offer->end_date < now())
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">⊗ Expirada</span>
                        @else
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">✕ Inactiva</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('admin.offers.edit', $offer) }}" class="text-blue-600 hover:text-blue-900">Editar</a>
                        <form action="{{ route('admin.offers.destroy', $offer) }}" method="POST" class="inline" data-action="delete-confirm" data-confirm-message="¿Eliminar esta oferta?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No hay ofertas creadas. 
                        <a href="{{ route('admin.offers.create') }}" class="text-orange-600 hover:underline">Crear la primera oferta</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $offers->links() }}
    </div>
</div>
@endsection
