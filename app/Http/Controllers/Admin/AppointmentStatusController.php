<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentStatusController extends Controller
{
    public function getStatusWithCount(): array
    {
        $cases = AppointmentStatus::$statuses;
        $result = [];

        foreach ($cases as $key => $status ) {
            $result[] = [
                'name'  => $status,
                'value' => $key,
                'count' => Appointment::where('status', $key)->count(),
                'color' => AppointmentStatus::getColorBy($key),
            ];
        }

        return $result;
    }
}
