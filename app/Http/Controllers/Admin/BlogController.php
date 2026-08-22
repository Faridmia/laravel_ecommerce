<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function list()
    {
        $data['getRecord'] = Blog::with('blogCategory')->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $data['header_title'] = 'Blogs';
        return view('admin.blog.list', $data);
    }

    public function add()
    {
        $data['getCategory'] = BlogCategory::getActiveCategories();
        $data['header_title'] = 'Add New Blog';
        return view('admin.blog.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $blog = new Blog();
        $blog->blog_category_id = $request->blog_category_id;
        $blog->title = trim($request->title);
        $blog->slug = Str::slug($request->title);
        
        // Generate unique slug if already exists
        $slug_count = Blog::where('slug', $blog->slug)->count();
        if ($slug_count > 0) {
            $blog->slug .= '-' . time();
        }

        $blog->short_description = trim($request->short_description);
        $blog->description = trim($request->description);
        $blog->tags = trim($request->tags);
        $blog->status = $request->status;
        $blog->created_by = auth()->id();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if (!file_exists(public_path('upload/blogs'))) {
                    mkdir(public_path('upload/blogs'), 0777, true);
                }
                $filename = 'blog_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/blogs/'), $filename);
                $blog->image = $filename;
            }
        }

        $blog->save();

        return redirect('admin/blog/list')->with('success', 'Blog created successfully');
    }

    public function edit($id)
    {
        $data['getRecord'] = Blog::findOrFail($id);
        $data['getCategory'] = BlogCategory::getActiveCategories();
        $data['header_title'] = 'Edit Blog';
        return view('admin.blog.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $blog = Blog::findOrFail($id);
        $blog->blog_category_id = $request->blog_category_id;
        $blog->title = trim($request->title);
        $blog->slug = Str::slug($request->title);
        
        // Generate unique slug if already exists for other posts
        $slug_count = Blog::where('slug', $blog->slug)->where('id', '!=', $id)->count();
        if ($slug_count > 0) {
            $blog->slug .= '-' . time();
        }

        $blog->short_description = trim($request->short_description);
        $blog->description = trim($request->description);
        $blog->tags = trim($request->tags);
        $blog->status = $request->status;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if (!empty($blog->image) && file_exists(public_path('upload/blogs/' . $blog->image))) {
                    @unlink(public_path('upload/blogs/' . $blog->image));
                }
                if (!file_exists(public_path('upload/blogs'))) {
                    mkdir(public_path('upload/blogs'), 0777, true);
                }
                $filename = 'blog_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/blogs/'), $filename);
                $blog->image = $filename;
            }
        }

        $blog->save();

        return redirect('admin/blog/list')->with('success', 'Blog updated successfully');
    }

    public function delete($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->is_delete = 1;
        $blog->save();

        return redirect('admin/blog/list')->with('success', 'Blog deleted successfully');
    }
}
