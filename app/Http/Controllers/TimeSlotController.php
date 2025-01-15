<?php

namespace App\Http\Controllers;

use App\Models\Psychologist;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    /**
     * Display a list of available time slots for a specific psychologist.
     *
     * @param int $id The ID of the psychologist.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($id)
    {
        // Retrieve all unbooked time slots for the specified psychologist
        $timeSlots = TimeSlot::where('psychologist_id', $id)
            ->where('is_booked', false)
            ->get();

        return response()->json($timeSlots);
    }

    /**
     * Store a newly created time slot for a specific psychologist.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id The ID of the psychologist.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {
        // Find the psychologist by ID
        $psychologist = Psychologist::find($id);

        if (!$psychologist) {
            return response()->json(['error' => 'Psychologist not found'], 404);
        }

        // Validate the incoming request
        $validated = $request->validate(
            [
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ],
            [
                'start_time.required' => 'Start time is required.',
                'end_time.required' => 'End time is required.',
                'end_time.after' => 'End time must be after the start time.',
            ]
        );

        // Ensure there are no overlapping time slots
        $overlaps = TimeSlot::where('psychologist_id', $id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
            })->exists();

        if ($overlaps) {
            return response()->json(['error' => 'Time slot overlaps with an existing one.'], 400);
        }

        // Create the time slot for the psychologist
        $timeSlot = $psychologist->timeSlots()->create($validated);

        return response()->json($timeSlot, 201);
    }

    /**
     * Update the booking status of a specific time slot.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id The ID of the time slot.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find the time slot by ID
        $timeSlot = TimeSlot::findOrFail($id);

        // Update the 'is_booked' status
        $timeSlot->update(['is_booked' => true]);

        return response()->json($timeSlot);
    }

    /**
     * Remove a specific time slot from storage.
     *
     * @param int $id The ID of the time slot.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find and delete the time slot
        TimeSlot::findOrFail($id)->delete();

        return response()->json(['message' => 'Time slot deleted successfully.']);
    }
}
