<?php

use App\Models\Filiere;
use App\Models\Niveau;
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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->string("nom")->nullable(false);
            $table->string('prenom')->nullable(false);
            $table->string('departement')->nullable(false);
            $table->string('photo')->nullable(false);
            $table->string('photo_diplome');
            $table->foreignIdFor(Filiere::class);
            $table->foreignIdFor(Niveau::class);
            $table->string('numero_gtin')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
