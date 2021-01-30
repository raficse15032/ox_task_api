<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('createdBy')->orderBy('id','DESC')->paginate(5);
        return response()->json(['products'=>$products],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
           'title'       => 'required',
           'price'       => 'required',
           'description' => 'required',
           'image'       => 'required|base64_jpg|base64_size',
        );

        $messsages = array(
           'base64_jpg'  => 'The image should be in \'jpg\' format',
           'base64_size' => 'The image should be in 1024 KB',
           'required'    => 'This field is required'
        );

        $validator = Validator::make($request->all(),$rules,$messsages);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $product = new Product;
        $extension = explode('/', mime_content_type($request->image))[1];
        $exploded = explode(',', $request->image);
        $decoded = base64_decode($exploded[1]);
        $fileName = Str::random(20).'.'.$extension;

        $path = public_path().'/image/product/'.$fileName;
        file_put_contents($path, $decoded);

        $product->image = $fileName;
        $product->price = $request->price;
        $product->title = $request->title;
        $product->description = $request->description;
        $product->user_id = 1;
        $product->save();

        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function check_base64_image($base64) {
        $img = imagecreatefromstring(base64_decode($base64));
        if (!imagecreatefromstring(base64_decode($base64))) {
            return false;
        }

        imagepng($img, 'tmp.png');
        $info = getimagesize('tmp.png');

        unlink('tmp.png');

        if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
            return true;
        }

        return false;
    }
}
