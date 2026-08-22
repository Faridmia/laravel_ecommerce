<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * List all CMS pages.
     */
    public function list()
    {
        $data['getRecord'] = Page::orderBy('id', 'asc')->get();
        $data['header_title'] = "CMS Pages";
        return view('admin.page.list', $data);
    }

    /**
     * Show page edit form.
     */
    public function edit($id)
    {
        $data['page'] = Page::findOrFail($id);
        $data['header_title'] = "Edit Page: " . $data['page']->title;
        return view('admin.page.edit', $data);
    }

    /**
     * Update page details.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        if ($page->slug == 'about') {
            $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'about_vision_title' => 'nullable|string|max:255',
                'about_vision_description' => 'nullable|string',
                'about_mission_title' => 'nullable|string|max:255',
                'about_mission_description' => 'nullable|string',
                'about_who_we_are_title' => 'nullable|string|max:255',
                'about_who_we_are_description' => 'nullable|string',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'meta_keywords' => 'nullable|string',
            ]);

            $page->title = trim($request->title);
            $page->about_vision_title = trim($request->about_vision_title);
            $page->about_vision_description = trim($request->about_vision_description);
            $page->about_mission_title = trim($request->about_mission_title);
            $page->about_mission_description = trim($request->about_mission_description);
            $page->about_who_we_are_title = trim($request->about_who_we_are_title);
            $page->about_who_we_are_description = trim($request->about_who_we_are_description);
        } else {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'meta_keywords' => 'nullable|string',
            ]);

            $page->title = trim($request->title);
            $page->description = $request->description;
        }

        // Handle page background image upload
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            if ($imageFile->isValid()) {
                // Delete old image if exists
                if (!empty($page->image) && file_exists(public_path('upload/pages/' . $page->image))) {
                    @unlink(public_path('upload/pages/' . $page->image));
                }

                if (!file_exists(public_path('upload/pages'))) {
                    mkdir(public_path('upload/pages'), 0777, true);
                }

                $imageName = 'page_' . $page->slug . '_' . time() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('upload/pages/'), $imageName);
                $page->image = $imageName;
            }
        }

        $page->meta_title = trim($request->meta_title);
        $page->meta_description = trim($request->meta_description);
        $page->meta_keywords = trim($request->meta_keywords);
        $page->save();

        return redirect()->route('admin.page.list')->with('success', 'Page content updated successfully.');
    }
}
