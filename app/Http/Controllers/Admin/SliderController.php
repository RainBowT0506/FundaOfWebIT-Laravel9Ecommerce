<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderFormRequest;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        return view('admin.slider.index', compact('sliders'));
    }
    public function create()
    {
        return view('admin.slider.create');
    }

    public function store(SliderFormRequest $request)
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move('uploads/slider/', $filename);
                $validatedData['image'] = "uploads/slider/$filename";
            }

            $validatedData['status'] = $request->status == true ? '1' : '0';

            $slider = Slider::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'image' => $validatedData['image'],
                'status' => $validatedData['status']
            ]);

            if ($slider) {
                return redirect('admin/slider')->with('message', 'Slider Added Successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to add Slider');
            }
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            Log::error('Error creating Slider: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add Slider: ' . $e->getMessage());
        }
    }

    public function edit(Slider $slider)
    {
        return view('admin.slider.edit', compact('slider'));
    }

    public function update(SliderFormRequest $request, Slider $slider)
    {
        try {
            $validatedData = $request->validated();


            $destination = $slider->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move('uploads/slider/', $filename);
                $validatedData['image'] = "uploads/slider/$filename";
            }

            $validatedData['status'] = $request->status == true ? '1' : '0';

            $slider = Slider::where('id', $slider->id)->update([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'image' => $validatedData['image'] ?? $slider->image,
                'status' => $validatedData['status']
            ]);

            if ($slider) {
                return redirect('admin/slider')->with('message', 'Slider Updated Successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update Slider');
            }
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            Log::error('Error creating Slider: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update Slider: ' . $e->getMessage());
        }
    }

    public function destroy(Slider $slider)
    {

        if ($slider->count() > 0) {
            $destination = $slider->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $slider->delete();
            return redirect('admin/slider')->with('message', 'Slider Deleted Successfully');
        }
        return redirect('admin/slider')->with('message', 'Something Went Wrong');
    }
}
