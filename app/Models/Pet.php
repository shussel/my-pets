<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use App\Enums\SexEnum;
use App\Enums\SpeciesEnum;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'species',
        'sex',
        'birth_date',
        'image'
    ];

    protected $casts = [
        'sex' => SexEnum::class,
        'species' => SpeciesEnum::class,
        'birth_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
