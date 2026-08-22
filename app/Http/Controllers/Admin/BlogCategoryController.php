<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function list()
    {
        $data['getRecord'] = BlogCategory::where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $data['header_title'] = 'Blog Categories';
        return view('admin.blog_category.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add New Blog Category';
        return view('admin.blog_category.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        $category = new BlogCategory();
        $category->name = trim($request->name);
        $category->slug = Str::slug($request->name);
        
        // Ensure slug is unique
        $slug_count = BlogCategory::where('slug', $category->slug)->count();
        if ($slug_count > 0) {
            $category->slug .= '-' . time();
        }

        $category->meta_title = trim($request->meta_title);
        $category->meta_description = trim($request->meta_description);
        $category->meta_keywords = trim($request->meta_keywords);
        $category->status = $request->status;
        $category->created_by = auth()->id();
        $category->save();

        return redirect('admin/blog-category/list')->with('success', 'Blog category created successfully');
    }

    public function edit($id)
    {
        $data['getRecord'] = BlogCategory::findOrFail($id);
        $data['header_title'] = 'Edit Blog Category';
        return view('admin.blog_category.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        $category = BlogCategory::findOrFail($id);
        $category->name = trim($request->name);
        $category->slug = Str::slug($request->name);
        
        // Ensure slug is unique for other posts
        $slug_count = BlogCategory::where('slug', $category->slug)->where('id', '!=', $id)->count();
        if ($slug_count > 0) {
            $category->slug .= '-' . time();
        }

        $category->meta_title = trim($request->meta_title);
        $category->meta_description = trim($request->meta_description);
        $category->meta_keywords = trim($request->meta_keywords);
        $category->status = $request->status;
        $category->save();

        return redirect('admin/blog-category/list')->with('success', 'Blog category updated successfully');
    }

    public function delete($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->is_delete = 1;
        $category->save();

        return redirect('admin/blog-category/list')->with('success', 'Blog category deleted successfully');
    }
}
