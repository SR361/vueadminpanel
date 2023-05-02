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

    public function index(){
        return Product::
                with('category','childcategory','productimages')
                ->latest()
                ->paginate(5);
        // return $product;
    }

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
        $imagename = $this->uploadFile(
            $product_image[0],
            'uploads/product'
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
                'uploads/productgallery'
            );

            ProductGalleryImages::create([
                'pid'   => $product->id,
                'image' => $galleryimagename
            ]);
        }

        return response()->json(['message' => 'success']);
    }

    public function edit(Product $product)
    {
        $products = Product::where('id',$product->id)->with('productimages')->first();
        return $products;
    }

    public function update(Product $product)
    {
        $validated = request()->validate([
            'name' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'description' => 'required',
            'parent_categorie_id' => 'required',
            'child_categorie_id' => 'required',
        ], [
            'parent_categorie_id.required' => 'The categorie field is required.',
            'child_categorie_id.required' => 'The sub-categorie field is required.',
        ]);
        if(request()->file('product_image')){
            $this->deleteFile(
                $product->getRawOriginal('image'),
                'uploads/product/'
            );

            $product_image = request()->file('product_image');
            $imagename = $this->uploadFile(
                $product_image[0],
                'uploads/product'
            );
            $validated['image'] = $imagename;
        }

        if(request()->file('product_gallery_image')){
            foreach(request()->file('product_gallery_image') as $image){
                $galleryimagename = $this->uploadFile(
                    $image,
                    'uploads/productgallery'
                );

                ProductGalleryImages::create([
                    'pid'   => $product->id,
                    'image' => $galleryimagename
                ]);
            }
        }

        $product->update($validated);

        return response()->json(['success' => true]);
    }

    public function productGalleryImage(Request $request){
        $galleryimage = ProductGalleryImages::where('pid',$request->id)->get();
        if(count($galleryimage) > 0){
            $output = array('status' => true, 'message' => 'Product gallery image found.', 'data' => $galleryimage);
        }else{
            $output = array('status' => false, 'message' => 'This product gallery image not found!');
        }

        return response()->json($output);
    }

    public function galleryImageDelete(ProductGalleryImages $productgalleryimage){
        $this->deleteFile(
            $productgalleryimage->getRawOriginal('image'),
            'uploads/productgallery/'
        );
        $productgalleryimage->delete();
        return response()->noContent();
    }

    public function destroy(Product $product)
    {
        if($product->getRawOriginal('image')){
            $this->deleteFile(
                $product->getRawOriginal('image'),
                'uploads/product/'
            );
        }
        $productgallery = ProductGalleryImages::where('pid',$product->id)->get();
        foreach($productgallery as $pg){
            $this->deleteFile(
                $pg->getRawOriginal('image'),
                'uploads/productgallery/'
            );
        }
        $product->delete();
        return response()->json(['success' => true], 200);
    }

    public function bulkDelete()
    {
        ProductGalleryImages::whereIn('id', request('ids'))->delete();

        return response()->json(['message' => 'Product gallery image deleted successfully!']);
    }


}
