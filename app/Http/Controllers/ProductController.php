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
    
    public function getProductSearch(Request $request)
    {
        $data['getColor'] = ColorModel::getRecordActive();
        $data['getBrand'] = BrandModel::getBrandRecordActive();
      

        $data['meta_title'] = "Search Product";
        $data['meta_description'] = '';
        $data['meta_keywords'] = '';
      
        $getProduct = ProductModel::getProduct(   );

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

    public function getCategorySub($slug, $sub_slug = null )
    {

        $getProductSingle = ProductModel::getSingleSlug( $slug );

        $getCategory = Category::getSingleSlug( $slug );
        $getSubCategory = SubCategoryModel::getSingleSlug( $sub_slug );

        $data['getColor'] = ColorModel::getRecordActive();
        $data['getBrand'] = BrandModel::getBrandRecordActive();

        if( !empty( $getProductSingle ) ) {
            $data['meta_title'] = $getProductSingle->product_title;
            $data['meta_description'] = $getProductSingle->short_description;

            $data['getProduct'] =  $getProductSingle;

            $data['getRelatedProduct'] = ProductModel::getRelatedProduct( $getProductSingle->id, $getProductSingle->sub_category_id );

            // Fetch product reviews and averages
            $data['reviews'] = \App\Models\ProductReviewModel::where('product_id', $getProductSingle->id)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->get();
            $data['avgRating'] = \App\Models\ProductReviewModel::where('product_id', $getProductSingle->id)
                ->where('status', 1)
                ->avg('rating') ?? 0;
            $data['reviewsCount'] = $data['reviews']->count();

            // Check if logged in user has purchased the product
            $data['userHasPurchased'] = false;
            if (auth()->check()) {
                $data['userHasPurchased'] = \DB::table('orders')
                    ->join('order_items', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.user_id', auth()->id())
                    ->where('order_items.product_id', $getProductSingle->id)
                    ->where('orders.status', '!=', 'cancelled')
                    ->exists();
            }

            return view( 'product.productdetails', $data );

        } elseif( !empty($getCategory) && !empty($getSubCategory) ) {
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
        } elseif( !empty($getCategory)  ) {
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
        } else {
            abort(404);
        }
       
      
    }

    public function shop()
    {
        $data['getColor'] = ColorModel::getRecordActive();
        $data['getBrand'] = BrandModel::getBrandRecordActive();
        $data['getProduct'] = ProductModel::getProduct();
        $data['getSubCategoryFilter'] = Category::getCategoryActive();
        $data['page'] = 0;

        return view('product.list', $data);
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

    public function submitReview(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->withErrors([
                'review' => 'You must be logged in to leave a review.'
            ]);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:5',
        ]);

        // Verify if user has actually purchased the product
        $hasPurchased = \DB::table('orders')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.user_id', auth()->id())
            ->where('order_items.product_id', $request->product_id)
            ->where('orders.status', '!=', 'cancelled')
            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->withErrors([
                'review' => 'Only customers who have purchased this product can leave a review.'
            ])->withInput();
        }

        $review = new \App\Models\ProductReviewModel();
        $review->product_id = $request->product_id;
        $review->rating = $request->rating;
        $review->review = trim($request->review);

        $user = auth()->user();
        $review->user_id = $user->id;
        $review->name = $user->name;
        $review->email = $user->email;

        $review->save();

        return redirect()->back()->with('success', 'Thank you! Your review has been submitted successfully.');
    }
}