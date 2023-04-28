<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\CustomFileUpload;
use App\Models\Product;
use App\Models\ProductGalleryImages;

class ProductController extends Controller
{
    use CustomFileUpload;

    public function store(){
        $validated = request()->validate([
            'name' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'description' => 'required',
            'parent_categorie_id' => 'required',
            'child_categorie_id' => 'required',
            'product_image' => 'required',
            'product_gallery_image' => 'required',
        ], [
            'parent_categorie_id.required' => 'The categorie field is required.',
            'child_categorie_id.required' => 'The sub-categorie field is required.',
        ]);

        $product_image = request()->file('product_image');
        // dd($product_image[0]);
        $imagename = $this->uploadFile(
            $product_image[0],
            'public/uploads/product'
        );

        $product = Product::create([
            'cid' => $validated['parent_categorie_id'],
            'sid' => $validated['child_categorie_id'],
            'name' => $validated['name'],
            'qty' => $validated['qty'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image' => $imagename,
            'status' => 1,
        ]);

        foreach(request()->file('product_gallery_image') as $image){
            $galleryimagename = $this->uploadFile(
                $image,
                'public/uploads/productgallery'
            );

            ProductGalleryImages::create([
                'pid'   => $product->id,
                'image' => $galleryimagename
            ]);
        }

        return response()->json(['message' => 'success']);
    }

    public function gallery_image_upload(Request $request){
        $path = storage_path('app/public/tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('image');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return ['name' => $name];
    }
}
