<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImport;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    /**
     * Mostrar formulario de importación de nuevos productos
     */
    public function index()
    {
        $imports = ProductImport::with('user')
            ->where('filename', 'LIKE', 'IMP%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.imports.new', compact('imports'));
    }

    /**
     * Procesar importación de nuevos productos
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');
        $originalFilename = $file->getClientOriginalName();
        
        // Generar nombre del archivo: IMPDDMMAAAHHMMSS.xlsx
        $filename = 'IMP' . now()->format('dmYHis') . '.xlsx';
        
        // Guardar archivo temporalmente
        $filePath = $file->storeAs('imports', $filename);
        
        // Crear registro de importación
        $import = ProductImport::create([
            'user_id' => Auth::id(),
            'filename' => $filename,
            'original_filename' => $originalFilename,
            'status' => 'processing',
        ]);

        try {
            // Leer el archivo Excel
            $spreadsheet = IOFactory::load(Storage::path($filePath));
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Validar cabeceras
            if (empty($rows)) {
                throw new \Exception('El archivo está vacío');
            }

            $headers = array_map('trim', $rows[0]);
            $expectedHeaders = ['CodArt', 'Descripcion', 'DescripcionMedida', 'Precio'];
            
            // Verificar cabeceras exactas
            if ($headers !== $expectedHeaders) {
                throw new \Exception('Las cabeceras del archivo no son correctas. Se esperan: ' . implode(', ', $expectedHeaders));
            }

            // Verificar que no haya columnas adicionales
            if (count($headers) > count($expectedHeaders)) {
                throw new \Exception('El archivo contiene columnas adicionales no permitidas');
            }

            // Obtener categoría "Importados"
            $importedCategory = Category::firstOrCreate(
                ['name' => 'Importados'],
                ['description' => 'Productos importados desde Excel']
            );

            $productsImported = 0;
            $productsSkipped = 0;
            $errors = [];

            // Procesar filas (omitir cabecera)
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                // Saltar filas vacías
                if (empty(array_filter($row))) {
                    continue;
                }

                $plu = trim($row[0] ?? '');
                $name = trim($row[1] ?? '');
                $descripcionMedida = trim($row[2] ?? '');
                $price = trim($row[3] ?? '');

                // Mapear DescripcionMedida a type: Un = N, Kg = P
                $type = 'N'; // Por defecto
                if (strtoupper($descripcionMedida) === 'KG') {
                    $type = 'P';
                } elseif (strtoupper($descripcionMedida) === 'UN') {
                    $type = 'N';
                }

                // Validar datos obligatorios
                if (empty($plu) || empty($name) || empty($price)) {
                    $errors[] = "Fila " . ($i + 1) . ": Faltan datos obligatorios (CodArt, Descripcion o Precio)";
                    continue;
                }

                // Validar que el precio sea numérico
                if (!is_numeric($price) || $price < 0) {
                    $errors[] = "Fila " . ($i + 1) . ": El precio no es válido";
                    continue;
                }

                // Verificar si el producto ya existe
                $existingProduct = Product::where('plu', $plu)->first();
                
                if ($existingProduct) {
                    $productsSkipped++;
                    continue;
                }

                // Generar slug único
                $baseSlug = Str::slug($name);
                $slug = $baseSlug;
                $counter = 1;
                
                // Verificar si el slug ya existe y agregar sufijo si es necesario
                while (Product::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                // Crear nuevo producto
                try {
                    Product::create([
                        'plu' => $plu,
                        'name' => $name,
                        'slug' => $slug,
                        'description' => $name, // Usar el mismo nombre como descripción
                        'type' => $type,
                        'price' => (float) $price,
                        'category_id' => $importedCategory->id,
                        'is_available' => 0,
                        'is_featured' => 0,
                    ]);
                    
                    $productsImported++;
                } catch (\Exception $e) {
                    $errors[] = "Fila " . ($i + 1) . ": Error al crear producto - " . $e->getMessage();
                }
            }

            // Determinar el estado de la importación
            $status = 'success';
            if ($productsImported === 0 && !empty($errors)) {
                $status = 'error';
            }

            // Actualizar registro de importación
            $import->update([
                'status' => $status,
                'products_imported' => $productsImported,
                'products_skipped' => $productsSkipped,
                'error_message' => !empty($errors) ? implode("\n", $errors) : null,
            ]);

            // Solo mostrar mensaje si hubo cambios o errores significativos
            if ($productsImported > 0 || $productsSkipped > 0) {
                $message = "Importación completada: {$productsImported} productos importados, {$productsSkipped} productos omitidos (ya existían)";
                
                if (!empty($errors)) {
                    $message .= ". Se encontraron algunos errores en el archivo.";
                }

                return redirect()->route('admin.imports.new')
                    ->with('success', $message);
            } else {
                // Si no se procesó nada, significa que hubo errores
                return redirect()->route('admin.imports.new')
                    ->with('error', 'No se importó ningún producto. Revisa los errores en el historial.');
            }

        } catch (\Exception $e) {
            // Actualizar registro como error
            $import->update([
                'status' => 'error',
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('admin.imports.new')
                ->with('error', 'Error en la importación: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de actualización masiva
     */
    public function indexUpdate()
    {
        $imports = ProductImport::with('user')
            ->where('filename', 'LIKE', 'UPD%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.imports.update', compact('imports'));
    }

    /**
     * Procesar actualización masiva de productos
     */
    public function processUpdate(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');
        $originalFilename = $file->getClientOriginalName();
        
        // Generar nombre del archivo: UPDDDMMAAAHHMMSS.xlsx
        $filename = 'UPD' . now()->format('dmYHis') . '.xlsx';
        
        // Guardar archivo
        $filePath = $file->storeAs('imports', $filename);
        
        // Crear registro de importación
        $import = ProductImport::create([
            'user_id' => Auth::id(),
            'filename' => $filename,
            'original_filename' => $originalFilename,
            'status' => 'processing',
        ]);

        try {
            // Leer el archivo Excel
            $spreadsheet = IOFactory::load(Storage::path($filePath));
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Validar cabeceras
            if (empty($rows)) {
                throw new \Exception('El archivo está vacío');
            }

            $headers = array_map('trim', $rows[0]);
            $expectedHeaders = ['CodArt', 'Descripcion', 'DescripcionMedida', 'Precio'];
            
            if ($headers !== $expectedHeaders) {
                throw new \Exception('Las cabeceras del archivo no son correctas. Se esperan: ' . implode(', ', $expectedHeaders));
            }

            if (count($headers) > count($expectedHeaders)) {
                throw new \Exception('El archivo contiene columnas adicionales no permitidas');
            }

            $productsUpdated = 0;
            $productsNotFound = 0;
            $errors = [];

            // Procesar filas
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                if (empty(array_filter($row))) {
                    continue;
                }

                $plu = trim($row[0] ?? '');
                $name = trim($row[1] ?? '');
                $descripcionMedida = trim($row[2] ?? '');
                $price = trim($row[3] ?? '');

                // Mapear DescripcionMedida a type: Un = N, Kg = P
                $type = 'N'; // Por defecto
                if (strtoupper($descripcionMedida) === 'KG') {
                    $type = 'P';
                } elseif (strtoupper($descripcionMedida) === 'UN') {
                    $type = 'N';
                }

                // Validar datos obligatorios
                if (empty($plu) || empty($name) || empty($price)) {
                    $errors[] = "Fila " . ($i + 1) . ": Faltan datos obligatorios (CodArt, Descripcion o Precio)";
                    continue;
                }

                // Validar precio
                if (!is_numeric($price) || $price < 0) {
                    $errors[] = "Fila " . ($i + 1) . ": El precio no es válido";
                    continue;
                }

                // Buscar producto existente por PLU
                $product = Product::where('plu', $plu)->first();
                
                if (!$product) {
                    $productsNotFound++;
                    $errors[] = "Fila " . ($i + 1) . ": Producto con PLU {$plu} no encontrado";
                    continue;
                }

                // Actualizar producto
                try {
                    $product->update([
                        'name' => $name,
                        'description' => $name,
                        'type' => $type,
                        'price' => (float) $price,
                    ]);
                    
                    // Actualizar slug si cambió el nombre
                    if ($product->wasChanged('name')) {
                        $baseSlug = Str::slug($name);
                        $slug = $baseSlug;
                        $counter = 1;
                        
                        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                            $slug = $baseSlug . '-' . $counter;
                            $counter++;
                        }
                        
                        $product->update(['slug' => $slug]);
                    }
                    
                    $productsUpdated++;
                } catch (\Exception $e) {
                    $errors[] = "Fila " . ($i + 1) . ": Error al actualizar producto - " . $e->getMessage();
                }
            }

            // Determinar estado
            $status = 'success';
            if ($productsUpdated === 0 && !empty($errors)) {
                $status = 'error';
            }

            // Actualizar registro de importación
            $import->update([
                'status' => $status,
                'products_imported' => $productsUpdated,
                'products_skipped' => $productsNotFound,
                'error_message' => !empty($errors) ? implode("\n", $errors) : null,
            ]);

            if ($productsUpdated > 0) {
                $message = "Actualización completada: {$productsUpdated} productos actualizados";
                
                if ($productsNotFound > 0) {
                    $message .= ", {$productsNotFound} productos no encontrados";
                }
                
                if (!empty($errors)) {
                    $message .= ". Se encontraron algunos errores en el archivo.";
                }

                return redirect()->route('admin.imports.update')
                    ->with('success', $message);
            } else {
                return redirect()->route('admin.imports.update')
                    ->with('error', 'No se actualizó ningún producto. Revisa los errores en el historial.');
            }

        } catch (\Exception $e) {
            $import->update([
                'status' => 'error',
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('admin.imports.update')
                ->with('error', 'Error en la actualización: ' . $e->getMessage());
        }
    }

    /**
     * Descargar archivo de importación
     */
    public function download($id)
    {
        $import = ProductImport::findOrFail($id);
        
        $filePath = 'imports/' . $import->filename;
        
        if (!Storage::exists($filePath)) {
            return redirect()->back()->with('error', 'El archivo no existe');
        }

        return Storage::download($filePath, $import->original_filename);
    }

    /**
     * Limpiar historial de importaciones
     */
    public function cleanHistory(Request $request)
    {
        $request->validate([
            'period' => 'required|in:1week,2weeks,1month',
        ]);

        $days = match($request->period) {
            '1week' => 7,
            '2weeks' => 14,
            '1month' => 30,
        };

        try {
            // No permitir borrar importaciones de menos de 7 días
            if ($days < 7) {
                return redirect()->back()->with('error', 'No se pueden borrar importaciones de menos de 1 semana');
            }

            $imports = ProductImport::olderThan($days)->get();
            
            $deletedCount = 0;
            foreach ($imports as $import) {
                $import->deleteFile();
                $import->delete();
                $deletedCount++;
            }

            return redirect()->back()
                ->with('success', "Se eliminaron {$deletedCount} importaciones y sus archivos");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al limpiar historial: ' . $e->getMessage());
        }
    }
}
