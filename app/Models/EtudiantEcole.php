<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EtudiantEcole extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }
}
