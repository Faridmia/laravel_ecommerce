<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BrandModel as Brand;
use Illuminate\Support\Str;
class BrandController extends Controller
{
    //
    public function list()
    {
        $data['getRecord'] = Brand::getRecord();
        $data['header_title'] = 'Brand';   
        return view('admin.brand.list', $data);
    }

    public function create()
    {
        $data['header_title'] = 'Add Brand';   
        return view('admin.brand.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $slug = Str::slug($request->name);

        if (Brand::where('slug', $slug)->count() > 0) {
            $slug = $slug . '-' . time();
        }

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = $slug;
        $brand->description = $request->description;
        $brand->status = $request->status;
        $brand->created_by = auth()->id();
        $brand->save();

        return redirect()->route('admin.brand.list')
            ->with('success', 'Brand created successfully.');
    }

    public function edit($id)
    {
        $brand = Brand::where('is_delete', 0)
            ->where('brand_id', $id)
            ->first();

        if (!$brand) {
            return redirect()->route('admin.brand.list')
                ->with('error', 'Brand not found.');
        }

        $data['getRecord'] = $brand;
        $data['header_title'] = 'Edit Brand';

        return view('admin.brand.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $brand = Brand::where('is_delete', 0)
            ->where('brand_id', $id)
            ->first();

        if (!$brand) {
            return redirect()->route('admin.brand.list')
                ->with('error', 'Brand not found.');
        }

        $slug = Str::slug($request->name);

        if (Brand::where('slug', $slug)->where('brand_id', '!=', $id)->count() > 0) {
            $slug = $slug . '-' . time();
        }

        $brand->name = $request->name;
        $brand->slug = $slug;
        $brand->description = $request->description;
        $brand->status = $request->status;
        $brand->save();

        return redirect()->route('admin.brand.list')
            ->with('success', 'Brand updated successfully.');
    }

    public function delete($id)
    {
        $brand = Brand::where('is_delete', 0)
            ->where('brand_id', $id)
            ->first();

        if (!$brand) {
            return redirect()->route('admin.brand.list')
                ->with('error', 'Brand not found.');
        }

        $brand->is_delete = 1;
        $brand->save();

        return redirect()->route('admin.brand.list')
            ->with('success', 'Brand deleted successfully.');
    }   
}
