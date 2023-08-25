<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        return Appointment::query()
            ->with('client:id,first_name,last_name')
            ->latest()
            ->paginate()
            ->through(fn($appointment) => [
                'id' => $appointment->id,
                'start_time' => $appointment->start_time->format('Y-m-d  h:i A'),
                'end_time' => $appointment->end_time->format('Y-m-d  h:i A'),
                'status' => $appointment->status,
                'client' => $appointment->client,
            ]);
    }
}
