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
        Schema::create('measurement_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garment_type_id')->constrained()->cascadeOnDelete();
            $table->string('key');        // chest, sleeve_length
            $table->string('label');      // Chest, Sleeve Length
            $table->string('unit')->default('inch');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['garment_type_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_fields');
    }
};
