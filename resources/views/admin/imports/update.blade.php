@extends('layouts.admin')

@section('title', 'Actualizar Productos Masivamente')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Encabezado -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Actualizar Productos Masivamente</h1>
            <p class="mt-2 text-sm text-gray-700">
                Sube un archivo Excel (.xlsx) para actualizar productos existentes
            </p>
        </div>
    </div>

    <!-- Formulario de carga -->
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-lg mb-8">
        <div class="px-6 py-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Cargar Archivo Excel</h2>
            
            <!-- Instrucciones -->
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Formato requerido del archivo:</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>Cabeceras obligatorias:</strong> CodArt, Descripcion, DescripcionMedida, Precio</li>
                                <li><strong>Se actualizarán:</strong> Descripcion (name y description), DescripcionMedida (type: Un=N, Kg=P) y Precio</li>
                                <li><strong>No</strong> se permiten cabeceras adicionales</li>
                                <li>Solo se actualizarán productos <strong>existentes</strong> (buscados por PLU/CodArt)</li>
                                <li>Los productos no encontrados serán <strong>ignorados</strong> y reportados</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <form action="{{ route('admin.imports.process-update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="updateForm">
                @csrf
                
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        Archivo Excel (.xlsx)
                    </label>
                    <input 
                        type="file" 
                        name="file" 
                        id="file" 
                        accept=".xlsx,.xls"
                        required
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-indigo-500 p-2.5"
                    >
                    @error('file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button 
                        type="submit"
                        id="submitBtn"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Actualizar Productos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Historial de actualizaciones -->
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-lg">
        <div class="px-6 py-6">
            <div class="sm:flex sm:items-center sm:justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Historial de Actualizaciones</h2>
                
                <!-- Botón de limpieza -->
                <div x-data="{ open: false }" class="relative mt-3 sm:mt-0">
                    <button 
                        @click="open = !open"
                        type="button"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="mr-2 -ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Limpiar Historial
                    </button>

                    <!-- Dropdown de opciones -->
                    <div 
                        x-show="open"
                        @click.away="open = false"
                        x-transition
                        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    >
                        <div class="py-1">
                            <form action="{{ route('admin.imports.clean') }}" method="POST" onsubmit="return confirm('¿Eliminar actualizaciones de más de 1 semana?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="period" value="1week">
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Más de 1 semana
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.imports.clean') }}" method="POST" onsubmit="return confirm('¿Eliminar actualizaciones de más de 2 semanas?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="period" value="2weeks">
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Más de 2 semanas
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.imports.clean') }}" method="POST" onsubmit="return confirm('¿Eliminar actualizaciones de más de 1 mes?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="period" value="1month">
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Más de 1 mes
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de historial -->
            @if($imports->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Sin actualizaciones</h3>
                    <p class="mt-1 text-sm text-gray-500">No hay historial de actualizaciones aún</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Fecha</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Usuario</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Archivo</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Estado</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Actualizados</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">No Encontrados</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($imports as $import)
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                        {{ $import->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $import->user->name }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-900">
                                        <div class="font-mono text-xs">{{ $import->filename }}</div>
                                        <div class="text-gray-500 truncate max-w-xs">{{ $import->original_filename }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @if($import->status === 'success')
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                Éxito
                                            </span>
                                        @elseif($import->status === 'error')
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                                Error
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                Procesando
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                        {{ $import->products_imported }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                        {{ $import->products_skipped }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <div class="flex gap-2">
                                            <a 
                                                href="{{ route('admin.imports.download', $import->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900"
                                                title="Descargar archivo"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </a>
                                            
                                            @if($import->error_message)
                                                <button 
                                                    type="button"
                                                    onclick="showErrorModal({{ json_encode($import->error_message) }})"
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Ver errores"
                                                >
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $imports->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para errores -->
<div 
    id="errorModal" 
    class="fixed inset-0 z-50 hidden overflow-y-auto"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                            Errores de Actualización
                        </h3>
                        <div class="mt-2">
                            <pre id="errorContent" class="text-sm text-gray-700 whitespace-pre-wrap bg-gray-50 p-4 rounded border border-gray-200 max-h-96 overflow-y-auto"></pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    type="button" 
                    onclick="closeErrorModal()"
                    class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de procesamiento -->
<div 
    id="processingModal" 
    class="fixed inset-0 z-50 hidden overflow-y-auto"
    aria-labelledby="processing-title" 
    role="dialog" 
    aria-modal="true"
>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block overflow-hidden text-center align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:max-w-md sm:w-full">
            <div class="px-4 pt-5 pb-4 bg-white sm:p-6">
                <div class="flex flex-col items-center">
                    <!-- Spinner -->
                    <div class="flex items-center justify-center w-16 h-16 mb-4">
                        <svg class="animate-spin h-12 w-12 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-2" id="processing-title">
                        Procesando Actualización
                    </h3>
                    
                    <div class="space-y-2 text-sm text-gray-600">
                        <p id="processingStep">Leyendo archivo Excel...</p>
                        <p class="text-xs text-gray-500">Por favor, no cierres esta ventana</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
