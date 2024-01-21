<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFormRequest;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.product.index');
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.product.create', compact('categories', 'brands'));
    }

    public function store(ProductFormRequest $request)
    {
        $validatedDate = $request->validated();

        $category = Category::findOrFail($validatedDate['category_id']);

        $product = $category->products()->create([
            'category_id' => $validatedDate['category_id'],
            'name' => $validatedDate['name'],
            'slug' => Str::slug($validatedDate['slug']),
            'brand' => $validatedDate['brand'],
            'small_description' => $validatedDate['small_description'],
            'description' => $validatedDate['description'],
            'original_price' => $validatedDate['original_price'],
            'selling_price' => $validatedDate['selling_price'],
            'quantity' => $validatedDate['quantity'],
            'trending' => $request->trending == true ? '1' : '0',
            'status' => $request->status == true ? '1' : '0',
            'meta_title' => $validatedDate['meta_title'],
            'meta_keyword' => $validatedDate['meta_keyword'],
            'meta_description' => $validatedDate['meta_description']
        ]);

        if ($request->hasFile('image')) {
            $uploadPath = 'uploads/product/';

            $i = 1;
            foreach ($request->file('image') as $imageFile) {
                $extension = $imageFile->getClientOriginalExtension();
                $filename = time() . $i++ . '.' . $extension;
                $imageFile->move($uploadPath, $filename);
                $finalImagePathName = $uploadPath . $filename;

                $product->productImages()->create([
                    'product_id' => $product->id,
                    'image' => $finalImagePathName
                ]);
            }
        }

        return redirect('/admin/product')->with('message', 'Product Added Successfully');
    }
}
