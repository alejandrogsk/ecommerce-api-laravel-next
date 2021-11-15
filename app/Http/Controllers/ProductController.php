<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;

use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('quantity', '>', 0)->get();

        foreach ($products as $product){
            $product->category->title;
        }

        return response()->json([
            'ok'=> true,
            'products'=> $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

    public function store(Request $request)
    {

        
        $request->validate([
            'name'=>'required',
            'price'=> 'required',
            'description'=>'required',
            'quantity'=> 'required',
            'category'=> 'required'
        ]);

        //Validate Category
        $category_validation = $this->validate_category(intval($request['category']));
        if( $category_validation['ok'] == false ){
            return [
                'ok'=> $category_validation['ok'],
                'message'=> $category_validation['message']
            ];
        } 
       
        //Store the image in filesystem
        if($file=$request->file('img')){
            //aviable extensions
            $allowed = array('png', 'jpg');
            //Get the img name
            $filename=$file->getClientOriginalName();
            //validate extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!in_array($ext, $allowed)) {
                return ['ok'=> false, 'message'=> 'Only accepted files with extension png or jpg'];
            }
            //if file extension is ok, storage the image /storage/aws.png
            // path = http://localhost/storage/aws.png
            $file->move('storage', $filename);
        } else {
            return response()->json([
                'ok'=> false,
                'message'=> 'Image is required'
            ]);
        }

        //encode
        $image_to_store = urlencode('/storage/'.$filename);

        $newProduct = Product::create([
            'name'=>$request['name'],
            'price'=> $request['price'],
            'description'=> $request['description'],
            'quantity'=> $request['quantity'],
            'img'=> $image_to_store,
            'category_id'=> $category_validation['category']
        ]);
        
        //Create the response object
        $response = response()->json([
            'ok'=> true,
            'message'=> 'Product created successfully',
            'product'=> $newProduct,
        ]);

        return $response;
    }


    /**
     * This function return if a product category is valid or not
     */
    private function validate_category($category_in_request) 
    {
        $categories = Category::all();

        foreach($categories as $category )
        {
            if ( $category['id'] === $category_in_request ) {
                return ['ok' => true, 'category' => $category['id']];
            }
        }

        return ['ok' => false, 'message'=> 'The category does not exist'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($productId)
    {
        //Find product
        $product = Product::findOrFail($productId);
   

        if($product->quantity == 0 ){
            return response()->json(['ok'=> false, 'message'=> "Don't have stock for that product."]);
        }

        //Get category data
        $product->category->title;
        //Return data
        return response()->json(['ok'=> true, 'product'=> $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        ($request->name) && $product->name = $request->name;
        ($request->price) && $product->price = $request->price;
        ($request->description) && $product->description = $request->description;
        ($request->name) && $product->name = $request->name;

        //Validate Category if exists
        if( $request->category){
            $category_validation = $this->validate_category(intval($request['category']));
            if( $category_validation['ok'] == false ){
                return [
                    'ok'=> $category_validation['ok'],
                    'message'=> $category_validation['message']
                ];
            } 
            $product->category_id = $category_validation['category'];
        }

        if($file=$request->file('img')){
            //Delete current image in the file system
            $image_name = urldecode($product->img);
            $image_to_delete = substr($image_name, strpos($image_name, "/") + 9);     
            Storage::disk('public')->delete($image_to_delete);

            //Store the new image
            //aviable extensions
            $allowed = array('png', 'jpg');
            //Get the img name
            $filename=$file->getClientOriginalName();
            //validate extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!in_array($ext, $allowed)) {
                return ['ok'=> false, 'message'=> 'Only accepted files with extension png or jpg'];
            }
            //if file extension is ok, storage the image /storage/image.png
            // path = http://localhost/storage/image.png
            $file->move('storage', $filename);

            //encode
            $image_to_store = urlencode('/storage/'.$filename);
            $product->img = $image_to_store;
        }
        

        $product->update();

        return ['ok'=> true, 'product'=> $product];

    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productId)
    {
        //find the product
        $product = Product::find($productId);

        if(!$product){
            return response()->json(["ok"=> false, "message"=> "product not found"]);
        }

        //get the product->img and decode it
        $image_name = urldecode($product->img);

        /*
            remove /storage from string:
            /storage/myimage.png - 9 = myimage.png . So now i can delet any image
        */
        $image_to_delete = substr($image_name, strpos($image_name, "/") + 9);     

        //delete the image from fs
        Storage::disk('public')->delete($image_to_delete);

        //delete the product
        $product->delete();


        //make the response
        return response()->json([
            'ok'=> true,
            'message'=> 'Product deleted successfully',
        ]);
    }

     /**
     * Search for a name
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        $products = Product::where('name','like', '%'.$name.'%')->get();
        
        if(count($products) === 0){
            return response()->json(['ok' => false, 'message' => 'None product has been found']);
        }

        foreach ($products as $product){
            $product->category->title;
        }

        return response()->json([
            'ok'=> true,
            'products'=> $products
        ]);
    }

    public function get_products_by_category($category_title)
    {
        $search_category = Category::where('title', $category_title)->get();

        if( count($search_category) == 0) return response()->json(['ok'=> false, 'message'=> "This category don't exist"]);

        $category = $search_category[0];

        $products = Product::where([['category_id', $category->id], ['quantity', '>', 0]])->get();

        if( count($products) == 0) return response()->json(['ok'=> false, 'message'=> "This category don't have any product yet"]);

        foreach($products as $product){
            $product->category;
        }

        return response()->json([
            'ok'=> true,
            'category' => $category, 
            'products' => $products,
        ]);
    }


    /**
     * This function is usefull in nextjs 
     * Recive the product name and return the full product
     */
    public function get_products_by_name($product_name)
    {
        $product = Product::where([['name', $product_name], ['quantity', '>', 0]])->first();

        if($product == null) return response()->json(['ok' => false, 'message'=> 'product not found']);

        $product->category;
        

        return response()->json([
            'ok'=> true,
            'product'=> $product
        ]);

    }
}