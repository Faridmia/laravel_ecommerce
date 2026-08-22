<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;

class SliderController extends Controller
{
    /**
     * List all sliders.
     */
    public function list()
    {
        $data['getRecord'] = Slider::orderBy('id', 'desc')->get();
        $data['header_title'] = "Sliders";
        return view('admin.slider.list', $data);
    }

    /**
     * Show add form.
     */
    public function add()
    {
        $data['header_title'] = "Add New Slider";
        return view('admin.slider.add', $data);
    }

    /**
     * Store new slider.
     */
    public function insert(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slider = new Slider();
        $slider->title = $request->title;
        $slider->subtitle = $request->subtitle;
        $slider->button_text = $request->button_text;
        $slider->button_link = $request->button_link;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if (!file_exists(public_path('upload/sliders'))) {
                    mkdir(public_path('upload/sliders'), 0777, true);
                }
                $filename = 'slider_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/sliders/'), $filename);
                $slider->image = $filename;
            }
        }

        $slider->save();
        return redirect()->route('admin.slider.list')->with('success', 'Slider added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $data['slider'] = Slider::findOrFail($id);
        $data['header_title'] = "Edit Slider";
        return view('admin.slider.edit', $data);
    }

    /**
     * Update slider.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slider = Slider::findOrFail($id);
        $slider->title = $request->title;
        $slider->subtitle = $request->subtitle;
        $slider->button_text = $request->button_text;
        $slider->button_link = $request->button_link;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                // Delete old image if it's stored in upload directory
                if (!empty($slider->image) && file_exists(public_path('upload/sliders/' . $slider->image))) {
                    @unlink(public_path('upload/sliders/' . $slider->image));
                }
                
                if (!file_exists(public_path('upload/sliders'))) {
                    mkdir(public_path('upload/sliders'), 0777, true);
                }
                $filename = 'slider_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/sliders/'), $filename);
                $slider->image = $filename;
            }
        }

        $slider->save();
        return redirect()->route('admin.slider.list')->with('success', 'Slider updated successfully.');
    }

    /**
     * Delete slider.
     */
    public function delete($id)
    {
        $slider = Slider::findOrFail($id);
        if (!empty($slider->image) && file_exists(public_path('upload/sliders/' . $slider->image))) {
            @unlink(public_path('upload/sliders/' . $slider->image));
        }
        $slider->delete();
        return redirect()->route('admin.slider.list')->with('success', 'Slider deleted successfully.');
    }
}
