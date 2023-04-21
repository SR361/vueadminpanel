<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;

class AppointmentController extends Controller
{
    public function index(){
        return Appointment::query()
                ->when(request('status'), function ($query) {
                    return $query->where('status', AppointmentStatus::from(request('status')));
                })
                ->with('client:id,first_name,last_name')
                ->latest()
                ->paginate()
                ->through(fn ($appoinment) => [
                    'id'         => $appoinment->id,
                    'start_time' => $appoinment->start_time->format('Y-m-d h:i A'),
                    'end_time'   => $appoinment->end_time->format('Y-m-d h:i A'),
                    'client'     => $appoinment->client,
                    'status' => [
                        'name' => $appoinment->status->name,
                        'color' => $appoinment->status->color(),
                    ],
                ]);
    }
}
