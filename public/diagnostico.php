<?php
// Archivo de diagnóstico - diagnostico.php
// Colocar en: public/diagnostico.php
// Acceder: https://tudominio.com/diagnostico.php

echo "<h1>Diagnóstico del Servidor</h1>";

echo "<h2>1. Versión de PHP</h2>";
echo "PHP Version: " . phpversion() . "<br>";

echo "<h2>2. Permisos de Storage</h2>";
$storagePath = __DIR__ . '/../storage/app/public';
echo "Storage Path: $storagePath<br>";
echo "Existe: " . (is_dir($storagePath) ? "✅ SÍ" : "❌ NO") . "<br>";
echo "Es escribible: " . (is_writable($storagePath) ? "✅ SÍ" : "❌ NO") . "<br>";
echo "Permisos: " . substr(sprintf('%o', fileperms($storagePath)), -4) . "<br>";

echo "<h2>3. Symlink de Storage</h2>";
$symlinkPath = __DIR__ . '/storage';
echo "Symlink Path: $symlinkPath<br>";
echo "Existe: " . (file_exists($symlinkPath) ? "✅ SÍ" : "❌ NO") . "<br>";
echo "Es symlink: " . (is_link($symlinkPath) ? "✅ SÍ" : "❌ NO") . "<br>";
if (is_link($symlinkPath)) {
    echo "Apunta a: " . readlink($symlinkPath) . "<br>";
}

echo "<h2>4. Permisos de Bootstrap/Cache</h2>";
$cachePath = __DIR__ . '/../bootstrap/cache';
echo "Cache Path: $cachePath<br>";
echo "Existe: " . (is_dir($cachePath) ? "✅ SÍ" : "❌ NO") . "<br>";
echo "Es escribible: " . (is_writable($cachePath) ? "✅ SÍ" : "❌ NO") . "<br>";

echo "<h2>5. Test de Escritura</h2>";
$testFile = $storagePath . '/test-' . time() . '.txt';
$writeTest = @file_put_contents($testFile, 'test');
if ($writeTest !== false) {
    echo "✅ Puede escribir en storage<br>";
    @unlink($testFile);
} else {
    echo "❌ NO puede escribir en storage<br>";
    echo "Error: " . error_get_last()['message'] . "<br>";
}

echo "<h2>6. Variables de Entorno</h2>";
echo "APP_ENV: " . getenv('APP_ENV') . "<br>";
echo "APP_DEBUG: " . getenv('APP_DEBUG') . "<br>";
echo "DB_CONNECTION: " . getenv('DB_CONNECTION') . "<br>";

echo "<h2>7. Logs recientes</h2>";
$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "Últimas líneas del log:<br>";
    echo "<pre style='background:#f5f5f5; padding:10px; max-height:300px; overflow:auto;'>";
    $lines = array_slice(file($logFile), -50);
    echo htmlspecialchars(implode('', $lines));
    echo "</pre>";
} else {
    echo "❌ No existe archivo de log<br>";
}

echo "<h2>8. Usuario actual</h2>";
echo "User: " . get_current_user() . "<br>";
echo "UID: " . getmyuid() . "<br>";
echo "GID: " . getmygid() . "<br>";

echo "<hr><p><strong>⚠️ ELIMINAR ESTE ARCHIVO DESPUÉS DE DIAGNOSTICAR</strong></p>";
