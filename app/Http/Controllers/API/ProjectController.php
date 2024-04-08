<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectMember;

class ProjectController extends Controller
{
    public function __construct(){
        $this->company_id = Auth::guard('api')->user()->company_id;
    }
    public function index(){
        $project = Project::
            latest()
            ->paginate(5);
        return $project;
    }

    public function store(Request $request){

        $project = Project::create([
            'company_id'        => $this->company_id,
            'short_code'        => $request->short_code,
            'project_name'      => $request->project_name,
            'start_date'        => $request->start_date,
            'deadline'          => $request->deadline,
            'category_id'       => $request->category_id,
            'client_id'         => $request->client_id,
            'project_summary'   => $request->project_summary,
            'notes'             => $request->notes,
            'currency_id'       => $request->currency_id,
            'project_budget'    => $request->project_budget,
            'hours_allocated'   => $request->hours_allocated,
        ]);
        foreach($request->project_member as $projectmember){
            $member = new ProjectMember();
            $member->company_id = $this->company_id;
            $member->user_id = $projectmember;
            $member->project_id = $project->id;
            $member->save();
        }
        return $project;
    }
}
