<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'client_id',
    //     'title',
    //     'description',
    //     'start_time',
    //     'end_time',
    //     'status',
    // ];

    protected $guarded = [];

    protected $appends = ['formatted_start_time', 'formatted_end_time'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'status' => AppointmentStatus::class,
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function formattedStartTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->start_time->format('Y-m-d h:i A'),
        );
    }

    public function formattedEndTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->end_time->format('Y-m-d h:i A'),
        );
    }

    public function getFormattedStartTimeAttribute()
    {
        return date('Y-m-d h:i A',strtotime($this->start_time));
        // return $this->start_time->format(config('app.date_format'));
    }

    public function getFormattedEndTimeAttribute()
    {
        return date('Y-m-d h:i A',strtotime($this->end_time));
        // return $this->end_time->format(config('app.date_format'));
    }
}
