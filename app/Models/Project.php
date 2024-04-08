<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'short_code',
        'project_name',
        'project_summary',
        'project_admin',
        'start_date',
        'deadline',
        'notes',
        'category_id',
        'client_id',
        'feedback',
        'manual_timelog',
        'client_view_task',
        'allow_client_notification',
        'completion_percent',
        'calculate_task_progress',
        'project_budget',
        'currency_id',
        'hours_allocated',
        'status',
        'user_id',
        'project_id'
    ];
}
