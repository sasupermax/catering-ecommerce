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
        Schema::create('product_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('filename'); // Nombre guardado: IMPDDMMAAAHHMMSS.xlsx
            $table->string('original_filename'); // Nombre original del archivo
            $table->enum('status', ['success', 'error', 'processing'])->default('processing');
            $table->text('error_message')->nullable();
            $table->integer('products_imported')->default(0);
            $table->integer('products_skipped')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_imports');
    }
};
