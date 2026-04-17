<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show(){
        return view('store',[
            // 'product_categories' => ProductCategory::all()

            'products'=> Product::with(['product_category'])->get()
        ]);
    }

    public function product_insert_form(){
        return view('product.insert-form',['product_categorries' =>ProductCategory::get()]
        
        );
    }

    public function insert_product(Request $request){
        $imageName = null;
        if($request->hasFile('image')){
            $imageName = time(). '-'. $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('product_image'), $imageName);
        

        }

        $product = new Product();
        $product->name = $request->name;
        $product->details = $request->details;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->product_category;
        $product->image_path = $imageName;
        $product->save();
        return redirect()->route('store');


    }
    
}
