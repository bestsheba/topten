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
        Schema::create('measurement_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('measurement_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('measurement_field_id')->constrained()->cascadeOnDelete();
            $table->decimal('value', 6, 2);
            $table->decimal('adjustment', 5, 2)->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->unique([
                'measurement_profile_id',
                'measurement_field_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_values');
    }
};
