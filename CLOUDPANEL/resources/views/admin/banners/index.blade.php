@extends('layouts.admin')

@section('title', 'Banners del Carousel')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Banners del Carousel</h1>
    <a href="{{ route('admin.banners.create') }}" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
        + Nuevo Banner
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orden</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Imagen</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vigencia</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200" id="banners-list">
            @forelse($banners as $banner)
            <tr data-id="{{ $banner->id }}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-900 font-semibold">{{ $banner->display_order }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($banner->image)
                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="h-16 w-28 object-cover rounded">
                    @else
                    <div class="h-16 w-28 bg-gray-200 rounded flex items-center justify-center">
                        <span class="text-gray-400 text-xs">Sin imagen</span>
                    </div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $banner->title }}</div>
                </td>
                <td class="px-6 py-4">
                    @if($banner->url)
                    <a href="{{ $banner->url }}" target="_blank" class="text-sm text-blue-600 hover:underline truncate block max-w-xs">
                        {{ Str::limit($banner->url, 40) }}
                    </a>
                    @else
                    <span class="text-sm text-gray-400">Sin enlace</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($banner->is_active && $banner->isValid())
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Activo</span>
                    @elseif($banner->is_active && !$banner->isValid())
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Programado</span>
                    @else
                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactivo</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-xs text-gray-500">
                        @if($banner->start_date)
                        Desde: {{ $banner->start_date->format('d/m/Y') }}<br>
                        @endif
                        @if($banner->end_date)
                        Hasta: {{ $banner->end_date->format('d/m/Y') }}
                        @endif
                        @if(!$banner->start_date && !$banner->end_date)
                        Siempre vigente
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.banners.edit', $banner) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline" data-action="delete-confirm" data-confirm-message="¿Estás seguro de eliminar este banner?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                    No hay banners registrados. <a href="{{ route('admin.banners.create') }}" class="text-orange-600 hover:underline">Crear el primero</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($banners->hasPages())
<div class="mt-4">
    {{ $banners->links() }}
</div>
@endif

@endsection
