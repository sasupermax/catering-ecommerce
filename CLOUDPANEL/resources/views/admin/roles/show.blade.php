@extends('layouts.admin')

@section('title', 'Detalles del Rol')
@section('page-title', 'Rol: ' . $role->name)

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $role->name }}</h3>
                <p class="text-sm text-gray-500 mt-1">Slug: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $role->slug }}</span></p>
            </div>
            <div class="flex space-x-2">
                @can('edit-roles')
                <a href="{{ route('admin.roles.edit', $role) }}" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                </a>
                @endcan
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Volver
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Description -->
            @if($role->description)
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Descripción</h4>
                <p class="text-gray-600">{{ $role->description }}</p>
            </div>
            @endif

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Usuarios</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $role->users_count }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Permisos</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $role->permissions_count }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Creado</p>
                            <p class="text-lg font-bold text-gray-900">{{ $role->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Permisos Asignados</h4>
                @if($role->permissions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($role->permissions as $permission)
                    <div class="bg-purple-50 border border-purple-100 rounded-lg p-3">
                        <p class="font-medium text-gray-900">{{ $permission->name }}</p>
                        <p class="text-xs text-gray-500 font-mono">{{ $permission->slug }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 italic">Este rol no tiene permisos asignados.</p>
                @endif
            </div>

            <!-- Users -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Usuarios con este Rol</h4>
                @if($role->users->count() > 0)
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Usuario</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Miembro desde</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($role->users as $user)
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-gray-500 italic">No hay usuarios asignados a este rol.</p>
                @endif
            </div>
        </div>

        <!-- Footer -->
        @can('delete-roles')
        @if(!in_array($role->slug, ['admin', 'editor', 'viewer']) && $role->users_count === 0)
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" data-action="delete-confirm" data-confirm-message="¿Estás seguro de que deseas eliminar este rol? Esta acción no se puede deshacer.">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Eliminar Rol
                </button>
            </form>
        </div>
        @endif
        @endcan
    </div>
</div>
@endsection
