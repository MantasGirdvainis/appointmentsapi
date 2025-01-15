<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of upcoming appointments.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve appointments where the associated time slot's start time is in the future
        $appointments = Appointment::whereHas('timeSlot', function ($query) {
            $query->where('start_time', '>=', now());
        })->with('timeSlot.psychologist')->get();

        return response()->json($appointments);
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate(
            [
                'time_slot_id' => 'required|exists:time_slots,id',
                'client_name' => 'required|string|max:255',
                'client_email' => 'required|email',
            ],
            [
                'time_slot_id.required' => 'The time slot ID is required.',
                'time_slot_id.exists' => 'The selected time slot does not exist.',
                'client_name.required' => 'The client name is required.',
                'client_email.email' => 'A valid email address is required.',
            ]
        );

        // Find the time slot by ID or throw a 404 error
        $timeSlot = TimeSlot::findOrFail($validated['time_slot_id']);

        // Check if the time slot is already booked
        if ($timeSlot->is_booked) {
            return response()->json(['error' => 'Time slot already booked.'], 400);
        }

        // Create the appointment and mark the time slot as booked
        $appointment = $timeSlot->appointment()->create($validated);
        $timeSlot->update(['is_booked' => true]);

        // Return the created appointment in the response
        return response()->json($appointment, 201);
    }
}
