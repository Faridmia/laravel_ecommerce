<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishlistModel;
use App\Models\ProductModel;

class HomeController extends Controller
{
    public function home()
    {
        $data['meta_title'] = 'E-commerce';
        $data['meta_description'] = 'This is home page description';
        $data['meta_keywords'] = 'home, page, keywords';
        return view('home');
    }

    public function wishlist(Request $request)
    {
        $data['wishlist'] = WishlistModel::where('user_id', auth()->id())
            ->with(['product.getImages']) // eager load relations
            ->orderBy('id', 'desc')
            ->get();

        $data['meta_title'] = "My Wishlist";
        $data['meta_description'] = "";
        $data['meta_keywords'] = "";

        return view('product.wishlist', $data);
    }

    public function addToWishlist($product_id)
    {
        $product = ProductModel::find($product_id);
        if (!$product) {
            return redirect()->back()->withErrors(['error' => 'Product not found.']);
        }

        $userId = auth()->id();

        // Check if already exists
        $exists = WishlistModel::where('user_id', $userId)
            ->where('product_id', $product_id)
            ->exists();

        if (!$exists) {
            $wish = new WishlistModel();
            $wish->user_id = $userId;
            $wish->product_id = $product_id;
            $wish->save();
        }

        return redirect()->back()->with('success', 'Product added to your wishlist successfully.');
    }

    public function removeFromWishlist($id)
    {
        $wish = WishlistModel::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $wish->delete();

        return redirect()->back()->with('success', 'Product removed from your wishlist successfully.');
    }

    public function compare(Request $request)
    {
        $compareIds = session()->get('compare', []);
        $products = ProductModel::whereIn('id', $compareIds)
            ->with(['getCategory'])
            ->get();

        $data['products'] = $products;
        $data['meta_title'] = "Compare Products";
        $data['meta_description'] = "";
        $data['meta_keywords'] = "";

        return view('product.compare', $data);
    }

    public function addToCompare($product_id)
    {
        $product = ProductModel::find($product_id);
        if (!$product) {
            return redirect()->back()->withErrors(['error' => 'Product not found.']);
        }

        $compare = session()->get('compare', []);

        if (in_array($product_id, $compare)) {
            return redirect()->back()->with('success', 'Product is already in compare list.');
        }

        if (count($compare) >= 4) {
            return redirect()->back()->withErrors(['error' => 'You can only compare up to 4 products at a time. Please remove an item first.']);
        }

        $compare[] = $product_id;
        session()->put('compare', $compare);

        return redirect()->back()->with('success', 'Product added to comparison successfully.');
    }

    public function removeFromCompare($product_id)
    {
        $compare = session()->get('compare', []);

        if (($key = array_search($product_id, $compare)) !== false) {
            unset($compare[$key]);
            session()->put('compare', array_values($compare));
        }

        return redirect()->back()->with('success', 'Product removed from comparison successfully.');
    }

    public function contact()
    {
        $data['meta_title'] = "Contact Us";
        $data['meta_description'] = "Get in touch with us";
        $data['meta_keywords'] = "contact, email, phone, support";
        
        return view('contact', $data);
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        \App\Models\ContactMessageModel::create([
            'name' => trim($request->name),
            'email' => trim($request->email),
            'phone' => trim($request->phone),
            'subject' => trim($request->subject),
            'message' => trim($request->message),
        ]);

        return redirect()->back()->with('success', 'Thank you! Your message has been sent successfully. We will get back to you shortly.');
    }
}
