<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryFormRequest;
use App\Models\Category;
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
}

