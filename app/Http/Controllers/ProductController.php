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
        $products = Product::with('createdBy')->paginate(5);
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
        $product = new Product;
        $extension = explode('/', mime_content_type($request->image_64))[1];
        $exploded = explode(',', $request->image_64);
        $decoded = base64_decode($exploded[1]);
        $fileName = Str::random(20).'.'.$extension;

        (int)(strlen(base64_decode($exploded[1]))/1024);

        // $img = imagecreatefromstring(base64_decode($exploded[1]));
        // if (!imagecreatefromstring(base64_decode($exploded[1]))) {
        //     return false;
        // }

        // imagepng($img, 'tmp.png');
        // $info = getimagesize('tmp.png');
        // unlink('tmp.png');


        // if ($this->check_base64_image($exploded[1])) {
        //     print 'Image!';
        // } else {
        //     print 'Not an image!';
        // }

        Validator::extend('base64_image', function ($attribute, $value, $parameters, $validator) {
            $exploded = explode(',', $value);
            $extension = array(explode('/', mime_content_type($value))[1]);
            $keyword = array('jpg','png','jpeg');
            if(0 < count(array_intersect(array_map('strtolower', $extension), $keyword)))
              {
                return true;
              }
              else{
                return false;

              }
        });

        $rules = array(
           'image_64' => 'required|base64_image'
        );

        $messsages = array(
           'base64_image' => 'এই ঘরটি পূরণ করা আবশ্যক',
        );

        $validator = Validator::make($request->all(),$rules,$messsages);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

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
