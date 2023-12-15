<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NiveauEcole extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    public function niveau():BelongsTo
    {
        return $this->belongsTo(Niveau::class);
    }

    public function ecole():BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }
}
