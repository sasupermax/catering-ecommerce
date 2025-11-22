@extends('layouts.admin')

@section('title', 'Roles')
@section('page-title', 'Gestión de Roles')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-gray-600">Administra los roles y permisos del sistema</p>
    @if(auth()->user()->hasPermission('create-roles'))
    <a href="{{ route('admin.roles.create') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
        + Crear Rol
    </a>
    @endif
</div>

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
    @foreach($errors->all() as $error)
    <p>{{ $error }}</p>
    @endforeach
</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuarios</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permisos</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($roles as $role)
            <tr>
                <td class="px-6 py-4">
                    <div>
                        <div class="text-sm font-semibold text-gray-900">{{ $role->name }}</div>
                        <div class="text-sm text-gray-500">{{ $role->slug }}</div>
                        @if($role->description)
                        <div class="text-xs text-gray-400 mt-1">{{ $role->description }}</div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $role->users_count }} usuarios
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                        {{ $role->permissions_count }} permisos
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                    @if(auth()->user()->hasPermission('view-roles'))
                    <a href="{{ route('admin.roles.show', $role) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                    @endif
                    @if(auth()->user()->hasPermission('edit-roles'))
                    <a href="{{ route('admin.roles.edit', $role) }}" class="text-orange-600 hover:text-orange-900">Editar</a>
                    @endif
                    @if(auth()->user()->hasPermission('delete-roles') && !in_array($role->slug, ['admin', 'editor', 'viewer']))
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline" data-action="delete-confirm" data-confirm-message="¿Estás seguro de eliminar este rol?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                    No hay roles registrados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $roles->links() }}
</div>
@endsection
