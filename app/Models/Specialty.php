<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    /** @use HasFactory<\Database\Factories\SpecialtyFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'is_active',
    ];

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'doctor_specialty', 'specialty_id', 'doctor_id');
    }
}
