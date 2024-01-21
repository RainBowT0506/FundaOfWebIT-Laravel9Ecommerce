<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryFormRequest;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index');
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(CategoryFormRequest $request)
    {
        $valid = $request->validated();

        $category = new Category;
        $category->name = $valid['name'];
        $category->slug = Str::slug($valid['slug']);
        $category->description = $valid['description'];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('uploads/category/', $filename);
            $category->image = $filename;
        }

        $category->meta_title = $valid['meta_title'];
        $category->meta_keyword = $valid['meta_keyword'];
        $category->meta_description = $valid['meta_description'];

        $category->status = $request->status == true ? '1' : '0';

        try {
            $category->save();
            return redirect('admin/category')->with('message', 'Category Added Successfully');
        } catch (\Exception $e) {
            \Log::error('Error saving category: ' . $e->getMessage());
            return redirect('admin/category')->with('message', $e->getMessage());
        }
    }


    public function edit(Category $category)
    {
        return view('admin/category/edit', compact('category'));
    }

    public function update(CategoryFormRequest $request, $categories)
    {
        $valid = $request->validated();

        $category = Category::findOrFail($categories);

        $category->name = $valid['name'];
        $category->slug = Str::slug($valid['slug']);
        $category->description = $valid['description'];

        if ($request->hasFile('image')) {

            $path = 'uploads/category/' . $category->image;
            if (File::exists($path)) {
                File::delete($path);
            }

            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('uploads/category/', $filename);
            $category->image = $filename;
        }

        $category->meta_title = $valid['meta_title'];
        $category->meta_keyword = $valid['meta_keyword'];
        $category->meta_description = $valid['meta_description'];

        $category->status = $request->status == true ? '1' : '0';

        try {
            $category->update();
            return redirect('admin/category')->with('message', 'Category Update Successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating category: ' . $e->getMessage());
            return redirect('admin/category')->with('message', $e->getMessage());
        }
    }
}
