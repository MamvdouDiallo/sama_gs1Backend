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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable(false);
            $table->string('prenoms')->nullable(false);
            $table->string('departement')->nullable(false);
            $table->string('numero_gtin')->nullable(false);
            $table->string('photo')->nullable(false);
            $table->string('photo_diplome')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
