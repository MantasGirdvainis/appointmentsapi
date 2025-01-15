<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'psychologist_id',
        'start_time',
        'end_time',
        'is_booked',
    ];

    /**
     * Get the psychologist associated with the time slot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function psychologist()
    {
        return $this->belongsTo(Psychologist::class);
    }

    /**
     * Get the appointment associated with the time slot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
}
