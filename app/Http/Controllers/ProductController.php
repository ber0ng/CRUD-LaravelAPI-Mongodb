<?php

namespace App\Http\Controllers;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class ProductController extends Controller
{
    //list all products
    public function index(){
        $products = Products::all();
        return response()->json(["result" => $products]);
    }

    //get single product
    public function getProduct($product_id){
        $product = Products::where('product_id', $product_id)->first();
        if (!$product) {
            return response()->json(['result' => 'Product not found'], 404);
        }
        return response()->json(['result' => $product]);
    }

    //for product id
    public function generateUniqueId() {
        $lastProduct = Products::orderBy('product_id', 'desc')->first();
        if (!$lastProduct) {
            // If there are no products in the database, start from 1.
            return '1';
        }
        $lastProductId = $lastProduct->product_id;
        $nextProductId = strval(intval($lastProductId) + 1);

        // Check if the generated $nextProductId already exists in the collection.
        // If it does, keep incrementing until a unique ID is found.
        while (Products::where('product_id', $nextProductId)->exists()) {
            $nextProductId = strval(intval($nextProductId) + 1);
        }
        return $nextProductId;
    }

    //add product
    public function store(Request $request){
        $product = new Products();
        $product->product_id = $this->generateUniqueId();
        $product->name = $request->input('name');
        $product->category = $request->input('category');
        $product->description = $request->input('description');
        $product->new_price = $request->input('new_price');
        $product->old_price = $request->input('old_price');
        $product->file_path = $request->file('image')->store('products');
        $product->save();
        return response()->json(['result'=>$product]);
    }

    //update products
    public function update(Request $request, $product_id) {

        $product = Products::where('product_id', $product_id)->first();
        \Log::info('Product ID received: ' . $product_id);
        Log::info("Product data before update: " . json_encode($product->toArray()));
        Log::info('Received request data: ', $request->all());
        if (!$product) {
            \Log::info('Product not found for ID: ' . $product_id);
            return response()->json(["result" => "Product not found"], 404);
        }
        $product->name = $request->input('name');
        $product->category = $request->input('category');
        $product->description = $request->input('description');
        $product->new_price = $request->input('new_price');
        $product->old_price = $request->input('old_price');
        $product->file_path = $request->file('image')->store('products');
        $product->save();
        Log::info("Product data after update: " . json_encode($product->toArray()));
        return response()->json(['result' => $product]);
    }

    //delete products
    public function destroy($product_id){
        $product = Products::where('product_id', $product_id)->first();
        if (!$product) {
            return response()->json(["result" => "Product not found"], 404);
        }
        $product->delete();
        return response()->json(['result' => $product]);
    }

    //delete all product
    public function destroyAll(){
        $products = Products::all();
        if ($products->isNotEmpty()) {
            $products->each(function($product) {
                $product->delete();
            });
            return response()->json(["result" => "All products deleted successfully"]);
        }
        return response()->json(["result" => "No products found to delete"]);
    }

    //search product
    public function search(Request $request){
        $query = $request->input('query');
        $product = Products::where('name', 'like', '%' . $query . '%')->get();
        return response()->json(['results' => $product]);
    }

    //filter product
    public function filter(Request $request){
        $query = $request->input('query');
        $product = Products::where('name', 'like', '%' . $query . '%');
        // Filter by category
        if ($request->has('category')) {
            $category = $request->input('category');
            $product = $product->where('category', $category);
        }

        // Filter by new_price
        if ($request->has('new_price')) {
            $newPrice = $request->input('new_price');
            $product = $product->where('new_price', '>=', $newPrice);
        }

        // Filter by old_price
        if ($request->has('old_price')) {
            $oldPrice = $request->input('old_price');
            $product = $product->where('old_price', '<=', $oldPrice);
        }

        //pagination
        $product = $product->paginate(10);
        return response()->json(['results' => $product]);
    }
}
