<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColorFormRequest;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{

    public function index()
    {
        $colors = Color::all();

        return view('admin.color.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.color.create');
    }

    public function store(ColorFormRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['status'] = $request->status == true ? '1' : '0';
        Color::create($validatedData);
        return redirect('admin/color')->with('message', 'Color Added Successfully');
    }

    public function edit(Color $color)
    {
        return view('admin/color/edit', compact('color'));
    }

    public function update(ColorFormRequest $request, $color_id)
    {
        $validatedData = $request->validated();
        $validatedData['status'] = $request->status == true ? '1' : '0';
        Color::find($color_id)->update($validatedData);
        return redirect('admin/color')->with('message', 'Color Updated Successfully');
    }

    public function destroy($color_id)
    {
        $color = Color::findOrFail($color_id);
        $color->delete();
        return redirect('admin/color')->with('message', 'Color Deleted Successfully');
    }
}
