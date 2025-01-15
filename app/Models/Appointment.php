<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'time_slot_id',
        'client_name',
        'client_email',
    ];

    /**
     * Get the associated time slot for the appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }
}
