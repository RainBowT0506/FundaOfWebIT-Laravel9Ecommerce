<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderFormRequest;
use App\Models\Slider;
use Illuminate\Http\Request;
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
            Log::info('Slider');

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
}
