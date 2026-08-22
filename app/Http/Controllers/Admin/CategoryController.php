<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Category;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    public function list()
    {
        $data['getRecord'] = Category::getCategoryList();
        $data['header_title'] = 'Category';   
        return view('admin.category.list', $data);
    }

    public function create()
    {
        $data['header_title'] = 'Category';  
        return view('admin.category.add');
    }

    public function store(Request $request)
    {
        // code to store category
         $request->validate([
            'name' => 'required',
            'meta_title' => 'required',
            'category_slug' => 'required|unique:categories,category_slug',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->category_slug = Str::slug($request->category_slug);
        $category->status = $request->status;
        $category->is_home = $request->is_home ? 1 : 0;
        $category->button_text = $request->button_text;
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if (!file_exists(public_path('upload/categories'))) {
                    mkdir(public_path('upload/categories'), 0777, true);
                }
                $filename = 'cat_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/categories/'), $filename);
                $category->image = $filename;
            }
        }

        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;
        $category->created_by = auth()->id();
        $category->save();

        return redirect('admin/category/list')->with('success', 'Category added successfully');
    }

    public function edit($id)
    {
        $data['getRecord'] = Category::findOrFail($id);
        $data['header_title'] = 'Edit Category'; 

        return view('admin.category.edit', $data );
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'meta_title' => 'required',
            'category_slug' => 'required|unique:categories,category_slug,'.$id,
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category->name = $request->name;
        $category->category_slug = Str::slug($request->category_slug);
        $category->status = $request->status;
        $category->is_home = $request->is_home ? 1 : 0;
        $category->button_text = $request->button_text;
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if (!empty($category->image) && file_exists(public_path('upload/categories/' . $category->image))) {
                    @unlink(public_path('upload/categories/' . $category->image));
                }
                if (!file_exists(public_path('upload/categories'))) {
                    mkdir(public_path('upload/categories'), 0777, true);
                }
                $filename = 'cat_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/categories/'), $filename);
                $category->image = $filename;
            }
        }

        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;
        $category->created_by = auth()->id();
        $category->save();
        return redirect('admin/category/list')->with('success', 'Category updated successfully');   
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        if (!empty($category->image) && file_exists(public_path('upload/categories/' . $category->image))) {
            @unlink(public_path('upload/categories/' . $category->image));
        }
        $category->delete();
        return redirect('admin/category/list')->with('success', 'Category deleted successfully');
    }
}
