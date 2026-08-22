@extends('layouts.app')

@section('content')
<main class="main">
    <div class="page-header text-center" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}')">
        <div class="container">
            <h1 class="page-title">Blog Classic<span>News & Articles</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->
    
    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-3">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Blog</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    @forelse($getBlogs as $value)
                        <article class="entry entry-classic">
                            <figure class="entry-media">
                                <a href="{{ url('blog/' . $value->slug) }}">
                                    <img src="{{ $value->getImageUrl() }}" alt="{{ $value->title }}" style="max-height: 450px; width: 100%; object-fit: cover;">
                                </a>
                            </figure><!-- End .entry-media -->

                            <div class="entry-body">
                                <div class="entry-meta">
                                    <span class="entry-author">by <a href="#">{{ $value->author->name ?? 'Admin' }}</a></span>
                                    <span class="meta-separator">|</span>
                                    <a href="#">{{ $value->created_at->format('M d, Y') }}</a>
                                </div><!-- End .entry-meta -->

                                <h2 class="entry-title">
                                    <a href="{{ url('blog/' . $value->slug) }}">{{ $value->title }}</a>
                                </h2><!-- End .entry-title -->

                                @if($value->blogCategory)
                                <div class="entry-cats">
                                    in <a href="{{ url('blog?category=' . $value->blogCategory->slug) }}">{{ $value->blogCategory->name }}</a>
                                </div><!-- End .entry-cats -->
                                @endif

                                <div class="entry-content">
                                    <p>{{ Str::limit(strip_tags($value->short_description), 200) }}</p>
                                    <a href="{{ url('blog/' . $value->slug) }}" class="read-more">Continue Reading</a>
                                </div><!-- End .entry-content -->
                            </div><!-- End .entry-body -->
                        </article><!-- End .entry -->
                    @empty
                        <div class="text-center py-5 text-secondary">
                            <h4>No blog posts found matching your criteria.</h4>
                            <a href="{{ url('blog') }}" class="btn btn-outline-primary-2 mt-2">View All Posts</a>
                        </div>
                    @endforelse

                    <div class="d-flex justify-content-center mt-5 mb-3">
                        {!! $getBlogs->appends(request()->query())->links('pagination::bootstrap-4') !!}
                    </div>
                </div><!-- End .col-lg-9 -->

                <aside class="col-lg-3">
                    <div class="sidebar">
                        <div class="widget widget-search">
                            <h3 class="widget-title">Search</h3><!-- End .widget-title -->

                            <form action="{{ url('blog') }}" method="GET">
                                <label for="ws" class="sr-only">Search in blog</label>
                                <input type="search" class="form-control" name="search" id="ws" placeholder="Search in blog" value="{{ request('search') }}" required>
                                <button type="submit" class="btn"><i class="icon-search"></i><span class="sr-only">Search</span></button>
                            </form>
                        </div><!-- End .widget -->

                        <div class="widget widget-cats">
                            <h3 class="widget-title">Categories</h3><!-- End .widget-title -->

                            <ul>
                                <li>
                                    <a href="{{ url('blog') }}" class="{{ empty(request('category')) ? 'text-primary font-weight-bold' : '' }}">All Categories</a>
                                </li>
                                @foreach($getBlogCategories as $cat)
                                    <li>
                                        <a href="{{ url('blog?category=' . $cat->slug) }}" class="{{ request('category') == $cat->slug ? 'text-primary font-weight-bold' : '' }}">{{ $cat->name }}<span>{{ $cat->blogs_count }}</span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div><!-- End .widget -->

                        <div class="widget">
                            <h3 class="widget-title">Popular Posts</h3><!-- End .widget-title -->

                            <ul class="posts-list">
                                @foreach($getRecentBlogs as $recent)
                                    <li>
                                        <figure>
                                            <a href="{{ url('blog/' . $recent->slug) }}">
                                                <img src="{{ $recent->getImageUrl() }}" alt="post" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                                            </a>
                                        </figure>

                                        <div>
                                            <span>{{ $recent->created_at->format('M d, Y') }}</span>
                                            <h4><a href="{{ url('blog/' . $recent->slug) }}">{{ $recent->title }}</a></h4>
                                        </div>
                                    </li>
                                @endforeach
                            </ul><!-- End .posts-list -->
                        </div><!-- End .widget -->

                        <div class="widget widget-banner-sidebar">
                            <div class="banner-sidebar-title">ad box 280 x 280</div><!-- End .ad-title -->
                            <div class="banner-sidebar">
                                <a href="#">
                                    <img src="{{ asset('assets/images/blog/sidebar/banner.jpg') }}" alt="banner">
                                </a>
                            </div><!-- End .banner-sidebar -->
                        </div><!-- End .widget -->
                    </div><!-- End .sidebar -->
                </aside><!-- End .col-lg-3 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main><!-- End .main -->
@endsection
