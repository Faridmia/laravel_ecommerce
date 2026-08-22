<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishlistModel;
use App\Models\ProductModel;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Models\SystemSetting;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function home()
    {
        $data['sliders'] = \App\Models\Slider::orderBy('id', 'asc')->get();
        $data['partners'] = \App\Models\Partner::orderBy('id', 'asc')->get();
        
        $data['trendy_categories'] = \App\Models\Category::where('categories.status', 0)
            ->where('categories.is_deleted', 0)
            ->where('categories.is_home', 1)
            ->whereHas('products')
            ->orderBy('categories.name', 'asc')
            ->get();
            
        $data['shop_categories'] = \App\Models\Category::where('categories.status', 0)
            ->where('categories.is_deleted', 0)
            ->orderBy('categories.name', 'asc')
            ->get();

        $data['getTrendyProducts'] = \App\Models\ProductModel::select('products.*', 'categories.name as category_name', 'categories.category_slug as category_slug', 'sub_category.name as sub_category_name', 'sub_category.category_slug as sub_category_slug')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('sub_category', 'sub_category.id', '=', 'products.sub_category_id')
            ->where('products.is_delete', 0)
            ->where('products.status', 0)
            ->orderBy('products.id', 'desc')
            ->limit(8)
            ->get();

        $data['recent_categories'] = $data['trendy_categories'];
        $data['recent_products'] = $data['getTrendyProducts'];

        $data['getRecentBlogs'] = \App\Models\Blog::where('status', 0)
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $data['meta_title'] = 'E-commerce';
        $data['meta_description'] = 'This is home page description';
        $data['meta_keywords'] = 'home, page, keywords';
        return view('home', $data);
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

    public function about()
    {
        $page = \App\Models\Page::getSlug('about');
        if (!$page) {
            abort(404);
        }
        $data['page'] = $page;
        $data['teams'] = \App\Models\Team::orderBy('id', 'asc')->get();
        $data['testimonials'] = \App\Models\Testimonial::orderBy('id', 'asc')->get();
        $data['meta_title'] = $page->meta_title;
        $data['meta_description'] = $page->meta_description;
        $data['meta_keywords'] = $page->meta_keywords;
        return view('about', $data);
    }

    public function terms()
    {
        $page = \App\Models\Page::getSlug('terms-condition');
        if (!$page) {
            abort(404);
        }
        $data['page'] = $page;
        $data['meta_title'] = $page->meta_title;
        $data['meta_description'] = $page->meta_description;
        $data['meta_keywords'] = $page->meta_keywords;
        return view('page', $data);
    }

    public function privacy()
    {
        $page = \App\Models\Page::getSlug('privacy-policy');
        if (!$page) {
            abort(404);
        }
        $data['page'] = $page;
        $data['meta_title'] = $page->meta_title;
        $data['meta_description'] = $page->meta_description;
        $data['meta_keywords'] = $page->meta_keywords;
        return view('page', $data);
    }

    public function contact()
    {
        $page = \App\Models\Page::getSlug('contact');
        $data['meta_title'] = $page->meta_title ?? "Contact Us";
        $data['meta_description'] = $page->meta_description ?? "Get in touch with us";
        $data['meta_keywords'] = $page->meta_keywords ?? "contact, email, phone, support";

        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session([
            'captcha_num1' => $num1,
            'captcha_num2' => $num2,
            'captcha_sum' => $num1 + $num2
        ]);
        
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
            'captcha' => 'required|integer',
        ]);

        if (intval($request->captcha) !== session('captcha_sum')) {
            return redirect()->back()->withErrors(['captcha' => 'The verification sum is incorrect. Please try again.'])->withInput();
        }

        $contact = \App\Models\ContactMessageModel::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'name' => trim($request->name),
            'email' => trim($request->email),
            'phone' => trim($request->phone),
            'subject' => trim($request->subject),
            'message' => trim($request->message),
        ]);

        // Send Email Notification
        $settings = SystemSetting::getSingle();
        $toEmail = !empty($settings->submit_email) ? $settings->submit_email : config('mail.from.address');

        if (!empty($toEmail)) {
            try {
                Mail::to($toEmail)->send(new ContactMail([
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'phone' => $contact->phone,
                    'subject' => $contact->subject,
                    'message' => $contact->message,
                ]));
            } catch (\Exception $e) {
                \Log::error('Contact Form Email Error: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Thank you! Your message has been sent successfully. We will get back to you shortly.');
    }

    public function loadMoreRecentArrivals(\Illuminate\Http\Request $request)
    {
        $offset = intval($request->input('offset', 8));
        $category_id = intval($request->input('category_id', 0));

        $query = \App\Models\ProductModel::select('products.*', 'categories.name as category_name', 'categories.category_slug as category_slug', 'sub_category.name as sub_category_name', 'sub_category.category_slug as sub_category_slug')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('sub_category', 'sub_category.id', '=', 'products.sub_category_id')
            ->where('products.is_delete', 0)
            ->where('products.status', 0);

        if ($category_id > 0) {
            $query->where('products.category_id', $category_id);
        }

        // Fetch 4 more products
        $products = $query->orderBy('products.id', 'desc')
            ->offset($offset)
            ->limit(4)
            ->get();

        $html = '';
        foreach ($products as $product) {
            $html .= view('product._single_trendy_card', ['product' => $product])->render();
        }

        // Check if there are even more products left to load
        $hasMoreQuery = \App\Models\ProductModel::where('products.is_delete', 0)
            ->where('products.status', 0);
        if ($category_id > 0) {
            $hasMoreQuery->where('products.category_id', $category_id);
        }
        $totalCount = $hasMoreQuery->count();
        $has_more = $totalCount > ($offset + $products->count());

        return response()->json([
            'html' => $html,
            'has_more' => $has_more
        ]);
    }

    public function blog(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Blog::with(['blogCategory', 'author'])
            ->where('status', 0)
            ->where('is_delete', 0);

        if (!empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if (!empty($request->category)) {
            $query->whereHas('blogCategory', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $data['getBlogs'] = $query->orderBy('id', 'desc')->paginate(5);

        $data['getBlogCategories'] = \App\Models\BlogCategory::where('status', 0)
            ->where('is_delete', 0)
            ->withCount(['blogs' => function ($q) {
                $q->where('status', 0)->where('is_delete', 0);
            }])
            ->get();

        $data['getRecentBlogs'] = \App\Models\Blog::where('status', 0)
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $data['meta_title'] = 'Blog - Molla eCommerce';
        $data['meta_description'] = 'Read the latest trends and guides on our blog.';
        $data['meta_keywords'] = 'blog, design, furniture, trends';

        return view('blog.list', $data);
    }

    public function blogDetail($slug)
    {
        $data['blog'] = \App\Models\Blog::with(['blogCategory', 'author'])
            ->where('slug', $slug)
            ->where('status', 0)
            ->where('is_delete', 0)
            ->firstOrFail();

        $data['comments'] = \App\Models\BlogComment::where('blog_id', $data['blog']->id)
            ->where('status', 1)
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->get();

        $data['relatedBlogs'] = \App\Models\Blog::where('blog_category_id', $data['blog']->blog_category_id)
            ->where('id', '!=', $data['blog']->id)
            ->where('status', 0)
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $data['getBlogCategories'] = \App\Models\BlogCategory::where('status', 0)
            ->where('is_delete', 0)
            ->withCount(['blogs' => function ($q) {
                $q->where('status', 0)->where('is_delete', 0);
            }])
            ->get();

        $data['getRecentBlogs'] = \App\Models\Blog::where('status', 0)
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $data['meta_title'] = $data['blog']->title . ' - Blog';
        $data['meta_description'] = Str::limit(strip_tags($data['blog']->short_description), 150);
        $data['meta_keywords'] = 'blog, ' . $data['blog']->title;

        return view('blog.detail', $data);
    }

    public function submitBlogComment(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'comment' => 'required|string',
        ]);

        $comment = new \App\Models\BlogComment();
        $comment->blog_id = $request->blog_id;
        $comment->comment = trim($request->comment);
        
        if (auth()->check()) {
            $comment->user_id = auth()->id();
            $comment->name = auth()->user()->name;
            $comment->email = auth()->user()->email;
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);
            $comment->name = trim($request->name);
            $comment->email = trim($request->email);
        }

        $comment->status = 0; // Pending approval
        $comment->save();

        return redirect()->back()->with('success', 'Your comment has been submitted and is pending admin approval.');
    }
}
