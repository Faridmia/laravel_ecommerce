<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategoryModel;
use App\Models\ColorModel;
use App\Models\ProductModel;
use App\Models\BrandModel;
class ProductController extends Controller
{
    

    public function getCategorySub($slug, $sub_slug = null )
    {
        $getCategory = Category::getSingleSlug( $slug );
        $getSubCategory = SubCategoryModel::getSingleSlug( $sub_slug );

        $data['getColor'] = ColorModel::getRecordActive();
        $data['getBrand'] = BrandModel::getBrandRecordActive();

        if( !empty($getCategory) && !empty($getSubCategory) )
        {
            $data['meta_title'] = $getCategory->meta_title;
            $data['meta_description'] = $getCategory->meta_description;
            $data['meta_keywords'] = $getCategory->meta_keywords;
            $data['getCategory'] = $getCategory;
            $data['getSubCategory'] = $getSubCategory;
            $data['getSubCategoryFilter'] = SubCategoryModel::getRecordSubCategory( $getCategory->id );
            $data['getProduct'] = ProductModel::getProduct( $getCategory->id, $getSubCategory->id  );
            return view('product.list', $data);
        } elseif( !empty($getCategory)  )
        {
            $data['getColor'] = ColorModel::getRecordActive();
            $data['getBrand'] = BrandModel::getBrandRecordActive();
            $data['getSubCategoryFilter'] = SubCategoryModel::getRecordSubCategory( $getCategory->id );

            $data['meta_title'] = $getCategory->meta_title;
            $data['meta_description'] = $getCategory->meta_description;
            $data['meta_keywords'] = $getCategory->meta_keywords;
            $data['getCategory'] = $getCategory;
            $data['getProduct'] = ProductModel::getProduct( $getCategory->id  );
            return view('product.list', $data);
        }
        else
        {
            abort(404);
        }
       
      
    }

    public function getFilterProductAjax( Request $request )
    {
        $getProduct = ProductModel::getProduct();

        return response()->json( [
            'status' => true,
            'success' => view('product._list', [
                'getProduct' => $getProduct
            ])->render(),
        ], 200 );
    }
}
