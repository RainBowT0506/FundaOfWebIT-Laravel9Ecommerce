<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFormRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $colors = Color::where('status', '0')->get();
        return view('admin.product.create', compact('categories', 'brands', 'colors'));
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

        if ($request->colors) {
            foreach ($request->colors as $key => $color) {
                $product->productColors()->create([
                    'product_id' => $product->id,
                    'color_id' => $color,
                    'quantity' => $request->color_quantity[$key] ?? 0
                ]);
            }
        }

        return redirect('/admin/product')->with('message', 'Product Added Successfully');
    }

    public function edit(int $product_id)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $product = Product::findOrFail($product_id);
        $product_color = $product->productColors->pluck('color_id')->toArray();
        $colors = Color::whereNotIn('id', $product_color)->get();

        return view('admin.product.edit', compact('categories', 'brands', 'product', 'colors'));
    }

    public function update(ProductFormRequest $request, int $product_id)
    {

        $validatedDate = $request->validated();


        // 我覺得這個查詢方式有問題，因為當前 select 的 category_id 與當 product 的 category id 不一致，所以找不到。
        // $product = Category::findOrFail($validatedDate['category_id'])->products()->where('id', $product_id)->first();

        $product = Product::findOrFail($product_id);

        if ($product) {
            $product->update([
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

            return redirect('/admin/product')->with('message', 'Product Updated Successfully');
        } else {
            return redirect('admin/product')->with('message', 'No Such Product Id Found');
        }
    }



    public function destroyImage(int $product_image_id)
    {
        Log::info('DestoryImage');
        $productImage = ProductImage::findOrFail($product_image_id);
        if (File::exists($productImage->image)) {
            File::delete($productImage->image);
        }

        $productImage->delete();

        return redirect()->back()->with('message', 'Product Image Deleted');
    }

    public function destroy($product_id)
    {
        $product = Product::findOrFail($product_id);

        if ($product->productImages()) {
            foreach ($product->productImages as $image) {
                if (File::exists($image->image)) {
                    File::delete($image->image);
                }
            }
        }

        $product->delete();

        return redirect()->back()->with('message', 'Product Deleted with all its image');
    }

    public function updateProdColorQty(Request $request, $prod_color_id)
    {
        Log::info("updateProdColorQty");
        $productColorData = Product::findOrFail($request->product_id)
            ->productColors()->where('id', $prod_color_id)->first();

        $productColorData->update(['quantity' => $request->qty]);

        return response()->json(['message' => 'Product Color Qty Updated']);
    }

    public function deleteProdColor($prod_color_id)
    {
        $prodColor = ProductColor::findOrFail($prod_color_id);
        $prodColor->delete();

        return response()->json(['message' => 'Product Color Deleted']);
    }
}
