<?php

use App\Models\Ecole;
use App\Models\niveau;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('niveau_ecoles', function (Blueprint $table) {
    //         $table->id();
    //         $table->foreignIdFor(niveau::class);
    //         $table->foreignIdFor(Ecole::class);
    //         $table->timestamps();
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveau_ecoles');
    }
};
