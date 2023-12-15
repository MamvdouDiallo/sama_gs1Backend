<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Etudiant extends Model
{
    protected $guarded = ['id'];
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    public function ecoles(): BelongsToMany
    {
        return $this->belongsToMany(Ecole::class, 'etudiant_ecoles', 'etudiant_id', 'ecole_id');
    }
}
