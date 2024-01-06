<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;
use App\Enums\SexEnum;
use App\Enums\SpeciesEnum;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        '_id',
        'name',
        'species',
        'sex',
        'weight',
        'birth_date',
        'avatar',
    ];

    protected $casts = [
        'sex' => SexEnum::class,
        'species' => SpeciesEnum::class,
        'birth_date' => 'date:Y-m-d'
    ];

    /**
     * Appends
     * @var array
     */
    protected $appends = [
        'age',
    ];

    public function getAgeAttribute(): string
    {
        if ($years = Carbon::parse($this->birth_date)->age) {
            return $years.' year'.($years > 1 ? 's' : '');
        }
        if ($months = Carbon::now()->diffInMonths(Carbon::parse($this->birth_date))) {
            return $months.' month'.($months > 1 ? 's' : '');
        }
        if ($weeks = Carbon::now()->diffInWeeks(Carbon::parse($this->birth_date))) {
            return $weeks.' week'.($weeks > 1 ? 's' : '');
        }
        if ($days = Carbon::now()->diffInDays(Carbon::parse($this->birth_date))) {
            return $days.' day'.($days > 1 ? 's' : '');
        }
        return 0;
    }
}
