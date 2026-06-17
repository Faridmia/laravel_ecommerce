<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Category;
use App\Models\SubCategoryModel;
use Illuminate\Support\Str;
class SubCategoryController extends Controller
{
    //
    public function list()
    {
        $data['getRecord'] = SubCategoryModel::getSubCategoryList();
        $data['header_title'] = 'Sub Category';   
        return view('admin.subcategory.list', $data);
    }

    public function create()
    {
        $data['getCategory'] = Category::getCategoryAll();
        $data['header_title'] = 'Sub Category';  
        return view('admin.subcategory.add', $data);
    }

    public function store(Request $request)
    {
        $data['header_title'] = 'Add Sub Category';
        $data['getCategoryList'] = Category::getCategoryList();

        if ($request->isMethod('post')) {

            $request->validate([
                'category_id'      => 'required',
                'sub_category_name'=> 'required',
                'category_slug'    => 'nullable',
                'meta_title'       => 'nullable',
                'status'           => 'required|in:0,1',
            ]);

            $model = new SubCategoryModel;
            $model->category_id      = $request->category_id;
            $model->name             = $request->sub_category_name;
            $model->category_slug    = Str::slug($request->category_slug);
            $model->status           = $request->status;
            $model->meta_title       = $request->meta_title;
            $model->meta_description = $request->meta_description;
            $model->meta_keywords    = $request->meta_keywords;
            $model->created_by       = Auth::user()->id;
            $model->save();

            return redirect()->route('admin.subcategory.list')
                ->with('success', 'Sub Category Added Successfully');
        }

        return view('admin.subcategory.add', $data);
    }

    public function edit($id)
    {
        $data['getRecord'] = SubCategoryModel::findOrFail($id);
        $data['getCategory'] = Category::getCategoryList();
        $data['header_title'] = 'Edit Sub Category';

        return view('admin.subcategory.edit', $data );
    }

    public function update(Request $request, $id)
    {
        $subcategory = SubCategoryModel::findOrFail($id);

        $request->validate([
            'category_id' => 'required',
            'sub_category_name' => 'required',
            'meta_title' => 'required',
            'category_slug' => 'required|unique:sub_category,category_slug,' . $id,
            'status' => 'required|in:0,1',
        ]);

        $subcategory->category_id = $request->category_id;
        $subcategory->name = $request->sub_category_name;
        $subcategory->category_slug = Str::slug($request->category_slug);
        $subcategory->status = $request->status;
        $subcategory->meta_title = $request->meta_title;
        $subcategory->meta_description = $request->meta_description;
        $subcategory->meta_keywords = $request->meta_keywords;
        $subcategory->created_by = auth()->id();

        $subcategory->save();

        return redirect()->route('admin.subcategory.list')
            ->with('success', 'Sub Category updated successfully');
    }

    public function delete($id)
    {
        $subcategory = SubCategoryModel::findOrFail($id);
        $subcategory->delete();
        return redirect()->route('admin.subcategory.list')->with('success', 'Sub Category deleted successfully');
    }

    
}
