<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    /**
     * List all testimonials.
     */
    public function list()
    {
        $data['getRecord'] = Testimonial::orderBy('id', 'desc')->get();
        $data['header_title'] = "Customer Testimonials";
        return view('admin.testimonial.list', $data);
    }

    /**
     * Show add form.
     */
    public function add()
    {
        $data['header_title'] = "Add Testimonial";
        return view('admin.testimonial.add', $data);
    }

    /**
     * Store new testimonial.
     */
    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'review' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $testimonial = new Testimonial();
        $testimonial->name = trim($request->name);
        $testimonial->designation = trim($request->designation);
        $testimonial->review = trim($request->review);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if (!file_exists(public_path('upload/testimonials'))) {
                    mkdir(public_path('upload/testimonials'), 0777, true);
                }
                $filename = 'testimonial_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/testimonials/'), $filename);
                $testimonial->image = $filename;
            }
        }

        $testimonial->save();
        return redirect()->route('admin.testimonial.list')->with('success', 'Testimonial added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $data['testimonial'] = Testimonial::findOrFail($id);
        $data['header_title'] = "Edit Testimonial";
        return view('admin.testimonial.edit', $data);
    }

    /**
     * Update testimonial.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'review' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->name = trim($request->name);
        $testimonial->designation = trim($request->designation);
        $testimonial->review = trim($request->review);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if (!empty($testimonial->image) && file_exists(public_path('upload/testimonials/' . $testimonial->image))) {
                    @unlink(public_path('upload/testimonials/' . $testimonial->image));
                }
                if (!file_exists(public_path('upload/testimonials'))) {
                    mkdir(public_path('upload/testimonials'), 0777, true);
                }
                $filename = 'testimonial_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/testimonials/'), $filename);
                $testimonial->image = $filename;
            }
        }

        $testimonial->save();
        return redirect()->route('admin.testimonial.list')->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Delete testimonial.
     */
    public function delete($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        if (!empty($testimonial->image) && file_exists(public_path('upload/testimonials/' . $testimonial->image))) {
            @unlink(public_path('upload/testimonials/' . $testimonial->image));
        }
        $testimonial->delete();
        return redirect()->route('admin.testimonial.list')->with('success', 'Testimonial deleted successfully.');
    }
}
