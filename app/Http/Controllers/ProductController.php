<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('updatedBy')->orderBy('id','DESC')->paginate(5);
        return response()->json(['products'=>$products],200);
    }

    public function store(CreateProductRequest $request)
    {

        $product   = new Product;
        
        $product->image       = $this->storeImage($request->image);
        $product->price       = $request->price;
        $product->title       = $request->title;
        $product->description = $request->description;
        $product->user_id     = auth()->user()->id;
        $product->save();

        $product->updated_by = auth()->user();

        return response()->json($product,201);
    }

    public function update(UpdateProductRequest $request)
    {
        
        $product = Product::find($request->id);

        if($request->not_edit_image != true){
            
            $path = public_path()."/image/product/".$product->image;
            unlink($path);

            $product->image = $this->storeImage($request->image);
        }
        
        $product->price       = $request->price;
        $product->title       = $request->title;
        $product->description = $request->description;
        $product->user_id     = auth()->user()->id;
        $product->update();

        $product->updated_by = auth()->user();
        return response()->json($product,200);
    }

    
    public function destroy($id)
    {
        $product = Product::find($id);
        $path = public_path()."/image/product/".$product->image;

        if(unlink($path) && $product->delete()){
            return response()->json(['product'=>'deleted successfully'],200);
        }
        else{
           return response()->json(['errors'=>'something went wrong'],204); 
        }

    }

    // public function check_base64_image($base64) {
    //     $img = imagecreatefromstring(base64_decode($base64));
    //     if (!imagecreatefromstring(base64_decode($base64))) {
    //         return false;
    //     }

    //     imagepng($img, 'tmp.png');
    //     $info = getimagesize('tmp.png');

    //     unlink('tmp.png');

    //     if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
    //         return true;
    //     }

    //     return false;
    // }

    protected function storeImage($data) {
        $extension = explode('/', mime_content_type($data))[1];
        $exploded  = explode(',', $data);
        $decoded   = base64_decode($exploded[1]);
        $fileName  = Str::random(20).'.'.$extension;

        $path = public_path().'/image/product/'.$fileName;
        file_put_contents($path, $decoded);
        return $fileName;
    }
}
