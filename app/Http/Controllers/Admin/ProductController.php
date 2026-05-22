<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductModel;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\SubCategoryModel;
use App\Models\BrandModel;
use App\Models\ColorModel;



class ProductController extends Controller
{
    //
    public function list()
    {
       
        $data['getRecord'] = ProductModel::getRecord();
        $data['header_title'] = 'Product';   
        return view( 'admin.product.list', $data );
    }

    public function create()
    {
        $data['categories'] = Category::getCategoryActive();
        $data['subcategories'] = SubCategoryModel::all();
        $data['brands'] = BrandModel::all();
        $data['colors'] = ColorModel::all();
        $data['header_title'] = 'Add Product';   
        return view( 'admin.product.add', $data );
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_title' => 'required',
        ]);

        $product = new ProductModel();
        $product->product_title = $request->product_title;
       
        $product->category_id = $request->category_id;
        $product->created_by = auth()->id();
        $product->save(); 

        $slug = Str::slug($request->product_title, '-' );
        if( ProductModel::checkSlug($slug) > 0 ) {
            $product->slug = $slug;
        } else {
            $new_slug = $slug . '-' . $product->id;
            $product->slug = $new_slug;
        }
        $product->save();
        return redirect()->route('admin.product.list')->with('success', 'Product created successfully.');   
    }

    public function getSubCategory(Request $request)
    {
        $getSubCategory = SubCategoryModel::where('category_id', $request->category_id)->get();

        $html = '<option value="">Select Sub Category</option>';

        foreach($getSubCategory as $subcategory)
        {
            $html .= '<option value="'.$subcategory->id.'">'.$subcategory->name.'</option>';
        }

        return response($html);
    }

    public function edit($id)
    {
        $product = ProductModel::getSingle($id);

        if( !$product ) {
            return redirect()->route('admin.product.list')->with('error', 'Product not found.');
        }

        $data['header_title'] = 'Edit Product';
        $data['product'] = $product;
        $data['categories'] = Category::all();
        $data['subcategories'] = SubCategoryModel::all();
        $data['brands'] = BrandModel::all();
        return view( 'admin.product.edit', $data );
        
    }

    
}