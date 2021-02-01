<?php
 
namespace App\Traits;
 
use Illuminate\Http\Request;
 
trait StoreImageTrait {
 
    public function storeImage(Request $request) {
 		$extension = explode('/', mime_content_type($data))[1];
        $exploded  = explode(',', $data);
        $decoded   = base64_decode($exploded[1]);
        $fileName  = Str::random(20).'.'.$extension;

        $path = public_path().'/image/product/'.$fileName;
        file_put_contents($path, $decoded);
        return $fileName;
    }
 
}
 