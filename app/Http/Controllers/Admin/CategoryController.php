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
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->category_slug = Str::slug($request->category_slug);
        $category->status = $request->status;
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
        ]);

        $category->name = $request->name;
        $category->category_slug = Str::slug($request->category_slug);
        $category->status = $request->status;
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
        $category->delete();
        return redirect('admin/category/list')->with('success', 'Category deleted successfully');
    }
}
