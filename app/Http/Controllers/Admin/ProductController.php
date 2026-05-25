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
use App\Models\ProductImageModel;


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

        if( $request->hasFile('image') ) {
            foreach( $request->file('image') as $key => $image ) {
                if( !$image->isValid() ) {
                    continue;
                } 

                $imageName = time() . '_' . $key . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload/products/'), $imageName);

                $productImage = new ProductImageModel();
                $productImage->product_id = $product->id;
                $productImage->image_name = $imageName;
                $productImage->image_extension = $image->getClientOriginalExtension();
                $productImage->order_by = ($key + 1) * 100;
                $productImage->save();
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
        $data['product'] = ProductModel::getSingle($id);

        $data['header_title'] = 'Edit Product';
        $data['categories'] = Category::all();
        $data['subcategories'] = SubCategoryModel::all();
        $data['brands'] = BrandModel::all();
        $data['colors'] = ColorModel::getRecordActive();

        return view('admin.product.edit', $data);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'product_title' => 'required',
            'price' => 'required',
        ]);

        $product = ProductModel::findOrFail($id);

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

        $slug = Str::slug($request->product_title);

        if (
            ProductModel::where('slug', $slug)
            ->where('id', '!=', $product->id)
            ->exists()
        ) {
            $product->slug = $slug . '-' . $product->id;
        } else {
            $product->slug = $slug;
        }

        $product->save();

        // Colors
        ProductColorModel::DeleteRecord($product->id);

        if(!empty($request->color_id)) {

            foreach($request->color_id as $color_id) {

                $productColor = new ProductColorModel();
                $productColor->product_id = $product->id;
                $productColor->color_id = $color_id;
                $productColor->save();
            }
        }

        // Sizes
        ProductSizeModel::DeleteRecord($product->id);

        if(!empty($request->size)) {

            foreach($request->size as $size) {

                if(empty($size['name']) && empty($size['price'])) {
                    continue;
                }

                $productSize = new ProductSizeModel();
                $productSize->product_id = $product->id;
                $productSize->name = $size['name'];
                $productSize->price = $size['price'] ?? 0;
                $productSize->save();
            }
        }

        // Images
        if($request->hasFile('image')) {

            foreach($request->file('image') as $key => $image) {

                if(!$image->isValid()) {
                    continue;
                }

                $imageName = time().'_'.$key.'.'.$image->getClientOriginalExtension();

                $image->move(
                    public_path('upload/products/'),
                    $imageName
                );

                $productImage = new ProductImageModel();
                $productImage->product_id = $product->id;
                $productImage->image_name = $imageName;
                $productImage->image_extension = $image->getClientOriginalExtension();
                $productImage->order_by = ($key + 1) * 100;
                $productImage->save();
            }
        }

        return redirect()
            ->route('admin.product.list')
            ->with('success', 'Product updated successfully.');
    }


    public function deleteImage($id)
    {
        $image = ProductImageModel::findOrFail($id);

        if( !empty($image->getImagesLogo())) {
            unlink(public_path('upload/products/' . $image->image_name));
        }

        $imagePath = public_path('upload/products/' . $image->image_name);


        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');

        
    }

    public function delete($id)
    {
        $product = ProductModel::findOrFail($id);
        $product->is_delete = 1;
        $product->save();

        return redirect()
            ->route('admin.product.list')
            ->with('success', 'Product deleted successfully.');
    }

    public function ProductImageOrder(Request $request)
    {

        if( !empty($request->photo_id)) {

            foreach($request->photo_id as $index => $id) {
                $image = ProductImageModel::find($id);

                if($image) {
                    $image->order_by = ($index + 1) * 100;
                    $image->save();
                }
            }
        }

        $json['success'] = true;

        echo json_encode($json);
    }

    
}