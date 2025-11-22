@extends('layouts.admin')

@section('title', 'Crear Rol')
@section('page-title', 'Crear Nuevo Rol')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.roles.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Rol *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                placeholder="Ej: Administrador, Editor, Cliente">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Slug (identificador) *</label>
            <input type="text" name="slug" value="{{ old('slug') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                placeholder="Ej: admin, editor, cliente">
            <p class="text-xs text-gray-500 mt-1">Usar solo minúsculas, números y guiones. Ejemplo: super-admin</p>
            @error('slug')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
            <textarea name="description" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                placeholder="Descripción opcional del rol">{{ old('description') }}</textarea>
            @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Permisos</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-4">
                @foreach($permissions as $permission)
                <label class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                        {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500 mt-1">
                    <div class="ml-3">
                        <span class="text-sm font-medium text-gray-900">{{ $permission->name }}</span>
                        <p class="text-xs text-gray-500">{{ $permission->slug }}</p>
                    </div>
                </label>
                @endforeach
            </div>
            @error('permissions')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.roles.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                Crear Rol
            </button>
        </div>
    </form>
</div>
@endsection
