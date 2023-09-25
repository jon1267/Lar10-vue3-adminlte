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
        Appointment::create([
            'title' => request('title'),
            'client_id' => 2,
            'start_time' => now(),
            'end_time' => now(),
            'description' => request('description'),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        return response()->json(['message' => 'success']);
    }
}
