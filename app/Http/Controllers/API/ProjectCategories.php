<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ProjectCategory;

class ProjectCategories extends Controller
{
    public function __construct(){
        $this->company_id = Auth::guard('api')->user()->company_id;
    }
    public function all(){
        $categorie = ProjectCategory::
            where('company_id',$this->company_id)
            ->get();

        return $categorie;
    }
    public function index(){
        $categorie = ProjectCategory::
                        where('company_id',$this->company_id)
                        ->latest()
                        ->paginate(10);
        return $categorie;
    }

    public function store(Request $request){
        $validated = request()->validate([
            'category_name'     => 'required',
        ]);

        $inserdata = array(
            'company_id' => $this->company_id,
            'category_name' => $request->category_name
        );
        $projectcategory = ProjectCategory::create($inserdata);
        return $projectcategory;
    }

    public function update(Request $request,ProjectCategory $projectcategorie){
        request()->validate([
            'category_name' => 'required',
        ]);
        $projectcategorie->update([
            'category_name' => $request->category_name,
        ]);
        return $projectcategorie;
    }

    public function destroy(ProjectCategory $projectcategorie)
    {
        $projectcategorie->delete();
        return response()->json(['success' => true], 200);
    }
}
