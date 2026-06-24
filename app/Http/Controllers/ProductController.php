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
            $getProduct = ProductModel::getProduct( $getCategory->id, $getSubCategory->id  );

            $page = 0;
            if( !empty( $getProduct->nextPageUrl() ) )
            {
               $parse_url = parse_url( $getProduct->nextPageUrl() );

               if( !empty( $parse_url['query'] ) )
               {
                   parse_str( $parse_url['query'], $query_array );
                   if( !empty( $query_array['page'] ) )
                   {
                       $page = $query_array['page'];
                   }
               }
            } 

            $data['page'] = $page;

            $data['getProduct'] =  $getProduct;

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
            $getProduct = ProductModel::getProduct( $getCategory->id  );

            $page = 0;
            if( !empty( $getProduct->nextPageUrl() ) )
            {
               $parse_url = parse_url( $getProduct->nextPageUrl() );

               if( !empty( $parse_url['query'] ) )
               {
                   parse_str( $parse_url['query'], $query_array );
                   if( !empty( $query_array['page'] ) )
                   {
                       $page = $query_array['page'];
                   }
               }
            } 

            $data['page'] = $page;

            $data['getProduct'] =  $getProduct;

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

         $page = 0;
        if( !empty( $getProduct->nextPageUrl() ) )
        {
            $parse_url = parse_url( $getProduct->nextPageUrl() );

            if( !empty( $parse_url['query'] ) )
            {
                parse_str( $parse_url['query'], $query_array );
                if( !empty( $query_array['page'] ) )
                {
                    $page = $query_array['page'];
                }
            }
        } 

        $data['page'] = $page;

        return response()->json( [
            'status' => true,
            "page" => $page,
            'success' => view('product._list', [
                'getProduct' => $getProduct
            ])->render(),
        ], 200 );
    }
}
