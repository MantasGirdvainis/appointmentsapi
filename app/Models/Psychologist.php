<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Psychologist extends Model
{
    use HasApiTokens, HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Get the time slots associated with the psychologist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }
}
