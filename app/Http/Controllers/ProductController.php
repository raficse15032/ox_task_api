<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Product;

class ProductController extends Controller
{
    
    public function index()
    {
        $products = Product::with('updatedBy')->orderBy('id','DESC')->paginate(5);
        return response()->json(['products'=>$products],200);
    }

    public function store(Request $request)
    {

        $rules = array(
           'title'       => 'required',
           'price'       => 'required',
           'description' => 'required',
           'image'       => 'required|base64_jpg|base64_size',
        );

        $messsages = array(
           'base64_jpg'  => 'The image should be in jpg or png or jpeg or gif format',
           'base64_size' => 'The image should less than 1024 KB',
           'required'    => 'This field is required'
        );

        $validator = Validator::make($request->all(),$rules,$messsages);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $product   = new Product;
        $extension = explode('/', mime_content_type($request->image))[1];
        $exploded  = explode(',', $request->image);
        $decoded   = base64_decode($exploded[1]);
        $fileName  = Str::random(20).'.'.$extension;

        $path = public_path().'/image/product/'.$fileName;
        file_put_contents($path, $decoded);

        $product->image       = $fileName;
        $product->price       = $request->price;
        $product->title       = $request->title;
        $product->description = $request->description;
        $product->user_id     = auth()->user()->id;
        $product->save();

        $product->updated_by = auth()->user();

        return response()->json($product,201);
    }

    public function update(Request $request)
    {
        if($request->not_edit_image == true){
            $rules = array(
               'title'       => 'required',
               'price'       => 'required',
               'description' => 'required',
            );
        }
        else{
            $rules = array(
               'title'       => 'required',
               'price'       => 'required',
               'description' => 'required',
               'image'       => 'required|base64_jpg|base64_size',
            );
        }
        

        $messsages = array(
           'base64_jpg'  => 'The image should be in jpg or png or jpeg or gif format',
           'base64_size' => 'The image should less than 1024 KB'
        );

        $validator = Validator::make($request->all(),$rules,$messsages);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $product = Product::find($request->id);

        if($request->not_edit_image != true){
            
            $path = public_path()."/image/product/".$product->image;
            unlink($path);

            $extension = explode('/', mime_content_type($request->image))[1];
            $exploded  = explode(',', $request->image);
            $decoded   = base64_decode($exploded[1]);
            $fileName  = Str::random(20).'.'.$extension;

            $path = public_path().'/image/product/'.$fileName;
            file_put_contents($path, $decoded);

            $product->image = $fileName;
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
}
