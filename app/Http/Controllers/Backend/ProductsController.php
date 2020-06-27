<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductImage;
use Image;

class ProductsController extends Controller
{
   public function __construct()
    {
      $this->middleware('auth:admin');
    }
  public function index()
  {

    $products = Product::orderby('id','desc')->get();
    return view("backend.pages.product.index")->with('products',$products);
  }

  public function create()
 {
   return view("backend.pages.product.create");
 }

 public function edit($id)
 {
     $products = Product::find($id);
     return view("backend.pages.product.edit")->with('products',$products);
 }



 public function store(Request $request)
 {
   $request->validate([
   'title' => 'required|max:255',
   'description' => 'required',
   'price' => 'required|numeric',
   'quantity' => 'required|numeric',
   'category_id' => 'required',
   'brand_id' => 'required',
 ]);

   $product = new Product;
   $product->title = $request->title;
   $product->description = $request->description;
   $product->price = $request->price;
   $product->quantity =$request->quantity;
   $product->slug = str_slug($request->title);
   $product->category_id = $request->category_id;
   $product->brand_id = $request->brand_id;
   $product->admin_id = 1;
   $product->save();

   //ProductImage Model Insert

   /*if ($request->hasFile('product_image')) {
     //insert that image
     $image = $request->file('product_image');
     $img = time() .'.'. $image->getClientOriginalExtension();
     $location = public_path('images/products/' .$img);
     Image::make($image)->save($location);

     $product_image = new ProductImage;
     $product_image->product_id = $product->id;
     $product_image->image = $img;
     $product_image->save();
   }*/
   if (count($request->product_image) > 0) {
     $i =0;
     foreach ($request->product_image as $image) {
       //$image = $request->file('product_image');
       $img = time() . $i. '.'. $image->getClientOriginalExtension();
       $location = public_path('images/products/' .$img);
       Image::make($image)->save($location);

       $product_image = new ProductImage;
       $product_image->product_id = $product->id;
       $product_image->image = $img;
       $product_image->save();
       $i++;
     }
   }
   return redirect()->route('admin.products');
   //return back();
 }
 public function update(Request $request, $id)
 {
   $request->validate([
   'title' => 'required|max:255',
   'description' => 'required',
   'price' => 'required|numeric',
   'quantity' => 'required|numeric',
   'category_id' => 'required',
   'brand_id' => 'required',
 ]);

   $product = Product::find($id);
   $product->title = $request->title;
   $product->description = $request->description;
   $product->price = $request->price;
   $product->quantity =$request->quantity;
   $product->slug = str_slug($request->title);
   $product->category_id = $request->category_id;
   $product->brand_id = $request->brand_id;
   $product->save();

   return redirect()->route('admin.products');
 }
 public function delete($id)
 {
    $products = Product::find($id);
    if (!is_null($products)) {
      $products->delete();
    }
  //Delete all item
  foreach($products->images as $img) {
    //Delete image from path
    $file_name = $img->image;
    if (file_exists("images/products/".$file_name)) {
        unlink("images/products/ ".$file_name);
    }
    $img->delete();
  }
  session()->flash('success', 'Product Has Deleted Successfully !!');
  return back();
  }
}