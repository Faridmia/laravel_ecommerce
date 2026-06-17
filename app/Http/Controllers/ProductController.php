<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategoryModel;
class ProductController extends Controller
{
    //

    public function getCategorySub($slug, $sub_slug = null )
    {
        $getCategory = Category::getSingleSlug( $slug );
        $getSubCategory = SubCategoryModel::getSingleSlug( $sub_slug );

        if( !empty($getCategory) && !empty($getSubCategory) )
        {
            $data['meta_title'] = $getCategory->meta_title;
            $data['meta_description'] = $getCategory->meta_description;
            $data['meta_keywords'] = $getCategory->meta_keywords;
            $data['getCategory'] = $getCategory;
            $data['getSubCategory'] = $getSubCategory;
            return view('product.list', $data);
        } elseif( !empty($getCategory) && empty($getSubCategory) )
        {
            $data['meta_title'] = $getCategory->meta_title;
            $data['meta_description'] = $getCategory->meta_description;
            $data['meta_keywords'] = $getCategory->meta_keywords;
            $data['getCategory'] = $getCategory;
            return view('product.list', $data);
        }
        else
        {
            abort(404);
        }
       
      
    }
}
