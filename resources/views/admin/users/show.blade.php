@extends('layouts.admin')

@section('title', 'Detalles del Usuario')
@section('page-title', 'Detalles del Usuario')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header con Avatar -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6">
            <div class="flex items-center">
                <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center text-3xl font-bold text-orange-600">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="ml-6 text-white">
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-orange-100">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Información Principal -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">ID del Usuario</h3>
                    <p class="text-lg text-gray-900">{{ $user->id }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Fecha de Registro</h3>
                    <p class="text-lg text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Última Actualización</h3>
                    <p class="text-lg text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Email Verificado</h3>
                    @if($user->email_verified_at)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        ✓ Verificado
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        Pendiente
                    </span>
                    @endif
                </div>
            </div>

            <!-- Roles Asignados -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Roles Asignados</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->roles as $role)
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold
                        @if($role->name === 'admin') bg-purple-100 text-purple-800
                        @elseif($role->name === 'editor') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($role->name) }}
                    </span>
                    @empty
                    <p class="text-gray-500 text-sm">No tiene roles asignados</p>
                    @endforelse
                </div>
            </div>

            <!-- Permisos Heredados -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Permisos (heredados de roles)</h3>
                @php
                    $permissions = $user->roles->flatMap->permissions->unique('id');
                @endphp
                
                @if($permissions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($permissions->sortBy('name') as $permission)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-sm bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    Este usuario no tiene permisos asignados. Considera asignarle un rol con permisos.
                </p>
                @endif
            </div>

            <!-- Acciones -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Volver al Listado
                </a>
                @if(auth()->user()->hasPermission('edit-users'))
                <a href="{{ route('admin.users.edit', $user) }}" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                    Editar Usuario
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
