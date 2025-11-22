<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'plu')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('plu')->nullable()->after('slug');
            });
        }
        
        // Generar PLUs para productos existentes que no tengan
        DB::statement('UPDATE products SET plu = LPAD(id, 6, "0") WHERE plu IS NULL OR plu = ""');
        
        // Hacer el campo unique y no nullable
        Schema::table('products', function (Blueprint $table) {
            $table->string('plu')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('plu');
        });
    }
};
