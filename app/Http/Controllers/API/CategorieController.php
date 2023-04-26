<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    public function index(){
        $categorie = Categorie::query()
        ->when(request('query'), function ($query, $searchQuery) {
            $query->where('name', 'like', "%{$searchQuery}%");
        })
        ->where('parent_id',0)
        ->latest()
        ->paginate(10);
        return $categorie;
    }

    public function store(Request $request){
        request()->validate([
            'name' => 'required',
            'slug' => 'required',
        ]);
        return $categorie = Categorie::create([
            'name' => $request->name,
            'slug'=> $request->slug,
            'parent_id' => 0,
            'type' => '',
            'image' => '',
        ]);
    }

    public function update(Request $request,Categorie $categorie){
        request()->validate([
            'name' => 'required',
            'slug' => 'required',
        ]);
        $categorie->update([
            'name' => $request->name,
            'slug'=> $request->slug,
        ]);
        return $categorie;
    }

    public function destory(Categorie $categorie)
    {
        $categorie->delete();

        return response()->noContent();
    }

    public function bulkDelete()
    {
        Categorie::whereIn('id', request('ids'))->delete();

        return response()->json(['message' => 'Categorie deleted successfully!']);
    }
}
