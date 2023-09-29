<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;

class AppointmentController extends Controller
{
    public function index()
    {
        //dd(request('status'));
        return Appointment::query()
            ->with('client:id,first_name,last_name')
            ->when(request('status'), function ($query) {
                return $query->where('status', request('status'));
            })
            ->latest()
            ->paginate()
            ->through(fn($appointment) => [
                'id' => $appointment->id,
                'start_time' => $appointment->start_time->format('Y-m-d  h:i A'),
                'end_time' => $appointment->end_time->format('Y-m-d  h:i A'),
                'status' => [
                    'name' => AppointmentStatus::getStatusBy($appointment->status),
                    'color' => AppointmentStatus::getColorBy($appointment->status)
                ],
                'client' => $appointment->client,
            ]);
    }

    public function store()
    {
        $validated = request()->validate([
            'client_id' => 'required',
            'title' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required',
        ], [
            'client_id.required' => 'The client name field is required.'
        ]);

        Appointment::create([
            'title' => $validated['title'],
            'client_id' => $validated['client_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'description' => $validated['description'],
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        return response()->json(['message' => 'success']);
    }

    public function edit(Appointment $appointment)
    {
        return $appointment;
    }

    public function update(Appointment $appointment)
    {
        $validated = request()->validate([
            'client_id' => 'required',
            'title' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required',
        ], [
            'client_id.required' => 'The client name field is required.'
        ]);

        $appointment->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete(); //dd($appointment, gettype($appointment));

        return response()->noContent();
    }
}
