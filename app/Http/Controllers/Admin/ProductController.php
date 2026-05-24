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
use App\Models\ProductColorModel;
use App\Models\ProductSizeModel;



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
        $data['brands'] = BrandModel::getBrandRecordActive();
        $data['colors'] = ColorModel::getRecordActive();
        $data['header_title'] = 'Add Product';   
        return view( 'admin.product.add', $data );

    }

    public function store(Request $request)
    {
        $request->validate([
            'product_title' => 'required',
            'price' => 'required',
        ]);

        $product = new ProductModel();

        $product->product_title = $request->product_title;
        $product->sku = $request->sku;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->brand_id = $request->brand_id;

        $product->price = $request->price;
        $product->sale_price = $request->sale_price;

        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->additional_information = $request->additional_information;
        $product->shipping_returns = $request->shipping_returns;

        $product->status = $request->status ?? 0;
        $product->created_by = auth()->id();

        $product->save();

        $slug = Str::slug($request->product_title);

        if (ProductModel::where('slug', $slug)->exists()) {
            $product->slug = $slug . '-' . $product->id;
        } else {
            $product->slug = $slug;
        }

        $product->save();

        ProductColorModel::DeleteRecord($product->id);

        if(!empty($request->color_id)) {
            foreach( $request->color_id as $color_id ) {
                $productColor = new ProductColorModel();
                $productColor->product_id = $product->id;
                $productColor->color_id = $color_id;
                $productColor->save();
            }
        }   

        ProductSizeModel::DeleteRecord( $product->id );

        if(!empty($request->size)) {
            foreach( $request->size as $size ) {
                if( empty($size['name']) && empty($size['price']) ) {
                    continue;
                }

                $productSize = new ProductSizeModel();
                $productSize->product_id = $product->id;
                $productSize->name = $size['name'];
                $productSize->price = !empty($size['price']) ? $size['price'] : 0.00;
                $productSize->save();
            }
        
        }  


        return redirect()
            ->route('admin.product.list')
            ->with('success', 'Product created successfully.');
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