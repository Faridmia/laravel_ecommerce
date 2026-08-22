<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReviewModel;

class ReviewController extends Controller
{
    /**
     * List all reviews with search filters.
     */
    public function list(Request $request)
    {
        $query = ProductReviewModel::select('product_reviews.*')
            ->join('products', 'products.id', '=', 'product_reviews.product_id')
            ->with(['product', 'user']);

        // Search by Product Title
        if ($request->filled('product_title')) {
            $query->where('products.product_title', 'like', '%' . trim($request->product_title) . '%');
        }

        // Search by Reviewer Name/Email
        if ($request->filled('reviewer')) {
            $reviewer = trim($request->reviewer);
            $query->where(function($q) use ($reviewer) {
                $q->where('product_reviews.name', 'like', '%' . $reviewer . '%')
                  ->orWhere('product_reviews.email', 'like', '%' . $reviewer . '%');
            });
        }

        // Search by Rating
        if ($request->filled('rating')) {
            $query->where('product_reviews.rating', $request->rating);
        }

        // Search by Status
        if ($request->filled('status')) {
            $query->where('product_reviews.status', $request->status);
        }

        // Date range
        if ($request->filled('start_date')) {
            $query->whereDate('product_reviews.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('product_reviews.created_at', '<=', $request->end_date);
        }

        $data['getRecord'] = $query->orderBy('product_reviews.id', 'desc')->paginate(20);
        $data['header_title'] = "Product Reviews";

        return view('admin.review.list', $data);
    }

    /**
     * Update review status via AJAX.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_reviews,id',
            'status' => 'required|in:0,1',
        ]);

        $review = ProductReviewModel::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();

        return response()->json([
            'status' => true,
            'message' => 'Review status updated successfully.'
        ]);
    }

    /**
     * Delete review record.
     */
    public function delete($id)
    {
        $review = ProductReviewModel::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
