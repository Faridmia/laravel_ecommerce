@extends('layouts.app')

@section('content')
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('blog') }}">Blog</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $blog->title }}</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <article class="entry entry-single">
                        <figure class="entry-media">
                            <img src="{{ $blog->getImageUrl() }}" alt="{{ $blog->title }}" style="max-height: 500px; width: 100%; object-fit: cover; border-radius: 4px;">
                        </figure><!-- End .entry-media -->

                        <div class="entry-body">
                            <div class="entry-meta">
                                <span class="entry-author">by <a href="#">{{ $blog->author->name ?? 'Admin' }}</a></span>
                                <span class="meta-separator">|</span>
                                <a href="#">{{ $blog->created_at->format('M d, Y') }}</a>
                                @if($blog->blogCategory)
                                <span class="meta-separator">|</span>
                                in <a href="{{ url('blog?category=' . $blog->blogCategory->slug) }}">{{ $blog->blogCategory->name }}</a>
                                @endif
                            </div><!-- End .entry-meta -->

                            <h2 class="entry-title">
                                {{ $blog->title }}
                            </h2><!-- End .entry-title -->

                            <div class="entry-content editor-content mt-4" style="color: #666; font-size: 1.5rem; line-height: 1.86;">
                                {!! $blog->description !!}
                            </div><!-- End .entry-content -->

                            <div class="entry-footer row no-gutters flex-column flex-sm-row mt-5 pt-3 border-top">
                                <div class="col">
                                    @if(!empty($blog->tags))
                                    <div class="entry-tags">
                                        <span>Tags:</span>
                                        @php
                                            $blog_tags = explode(',', $blog->tags);
                                        @endphp
                                        @foreach($blog_tags as $tag)
                                            @php
                                                $trimmed_tag = trim($tag);
                                            @endphp
                                            @if(!empty($trimmed_tag))
                                                <a href="{{ url('blog?search=' . urlencode($trimmed_tag)) }}">{{ $trimmed_tag }}</a>
                                            @endif
                                        @endforeach
                                    </div><!-- End .entry-tags -->
                                    @endif
                                </div><!-- End .col -->

                                <div class="col-auto mt-2 mt-sm-0">
                                    <div class="social-icons social-icons-color">
                                        <span class="social-label">Share this post:</span>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" class="social-icon social-facebook" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->title) }}" class="social-icon social-twitter" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                        <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&media={{ urlencode($blog->getImageUrl()) }}&description={{ urlencode(strip_tags($blog->short_description)) }}" class="social-icon social-pinterest" title="Pinterest" target="_blank"><i class="icon-pinterest"></i></a>
                                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($blog->title) }}" class="social-icon social-linkedin" title="Linkedin" target="_blank"><i class="icon-linkedin"></i></a>
                                    </div><!-- End .social-icons -->
                                </div><!-- End .col-auto -->
                            </div><!-- End .entry-footer-row -->
                        </div><!-- End .entry-body -->
                    </article><!-- End .entry -->

                    <div class="related-posts mt-5">
                        <h3 class="title text-center mb-4">Related Posts</h3><!-- End .title text-center -->
                        @if($relatedBlogs->count() > 0)
                            <div class="row">
                                @foreach($relatedBlogs as $related)
                                    <div class="col-sm-4">
                                        <div class="entry entry-grid text-center">
                                            <figure class="entry-media">
                                                <a href="{{ url('blog/' . $related->slug) }}">
                                                    <img src="{{ $related->getImageUrl() }}" alt="{{ $related->title }}" style="height: 180px; object-fit: cover;">
                                                </a>
                                            </figure><!-- End .entry-media -->

                                            <div class="entry-body">
                                                <div class="entry-meta">
                                                    <a href="#">{{ $related->created_at->format('M d, Y') }}</a>
                                                </div><!-- End .entry-meta -->

                                                <h3 class="entry-title">
                                                    <a href="{{ url('blog/' . $related->slug) }}">{{ $related->title }}</a>
                                                </h3><!-- End .entry-title -->
                                            </div><!-- End .entry-body -->
                                        </div><!-- End .entry -->
                                    </div><!-- End .col-sm-4 -->
                                @endforeach
                            </div><!-- End .row -->
                        @else
                            <p class="text-center text-muted">No related posts found in this category.</p>
                        @endif
                    </div><!-- End .related-posts -->

                    <div class="comments mt-5">
                        <h3 class="title">{{ $comments->count() }} {{ $comments->count() == 1 ? 'Comment' : 'Comments' }}</h3><!-- End .title -->

                        @if($comments->count() > 0)
                            <ul>
                                @foreach($comments as $comment)
                                    <li class="mb-4">
                                        <div class="comment">
                                            <figure class="comment-media">
                                                <a href="#">
                                                    <img src="{{ asset('assets/images/blog/comments/1.jpg') }}" alt="User name" style="width: 50px; height: 50px; border-radius: 50%;">
                                                </a>
                                            </figure>

                                            <div class="comment-body">
                                                <div class="comment-user">
                                                    <h4><a href="#">{{ $comment->name }}</a></h4>
                                                    <span class="comment-date">{{ $comment->created_at->format('M d, Y \a\t g:i a') }}</span>
                                                </div><!-- End .comment-user -->

                                                <div class="comment-content">
                                                    <p>{{ $comment->comment }}</p>
                                                </div><!-- End .comment-content -->
                                            </div><!-- End .comment-body -->
                                        </div><!-- End .comment -->
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No approved comments yet. Be the first to comment!</p>
                        @endif
                    </div><!-- End .comments -->

                    <div class="reply mt-5">
                        <div class="heading-left">
                            <h3 class="title">Leave A Reply</h3><!-- End .title -->
                            <p class="title-desc text-muted">Your email address will not be published. Required fields are marked *</p>
                        </div><!-- End .heading -->

                        @include('admin.layouts._message')

                        <form action="{{ route('blog.comment.submit') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="blog_id" value="{{ $blog->id }}">

                            <textarea class="form-control mb-3" name="comment" cols="30" rows="4" placeholder="Comment *" required></textarea>

                            @if(!auth()->check())
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control" name="name" placeholder="Name *" required>
                                    </div><!-- End .col-md-6 -->

                                    <div class="col-md-6 mb-3">
                                        <input type="email" class="form-control" name="email" placeholder="Email *" required>
                                    </div><!-- End .col-md-6 -->
                                </div><!-- End .row -->
                            @endif

                            <button type="submit" class="btn btn-outline-primary-2">
                                <span>POST COMMENT</span>
                                <i class="icon-long-arrow-right"></i>
                            </button>
                        </form>
                    </div><!-- End .reply -->

                    <nav class="pager-nav mt-5" aria-label="Page navigation">
                        <a class="pager-link pager-link-prev" href="{{ url('blog') }}" aria-label="Previous" tabindex="-1">
                            <span class="pager-link-title">Back to Blog</span>
                        </a>
                    </nav>
                </div><!-- End .col-lg-9 -->

                <aside class="col-lg-3">
                    <div class="sidebar">
                        <div class="widget widget-search">
                            <h3 class="widget-title">Search</h3><!-- End .widget-title -->

                            <form action="{{ url('blog') }}" method="GET">
                                <label for="ws" class="sr-only">Search in blog</label>
                                <input type="search" class="form-control" name="search" id="ws" placeholder="Search in blog" required>
                                <button type="submit" class="btn"><i class="icon-search"></i><span class="sr-only">Search</span></button>
                            </form>
                        </div><!-- End .widget -->

                        <div class="widget widget-cats">
                            <h3 class="widget-title">Categories</h3><!-- End .widget-title -->

                            <ul>
                                <li>
                                    <a href="{{ url('blog') }}">All Categories</a>
                                </li>
                                @foreach($getBlogCategories as $cat)
                                    <li>
                                        <a href="{{ url('blog?category=' . $cat->slug) }}" class="{{ $blog->blog_category_id == $cat->id ? 'text-primary font-weight-bold' : '' }}">{{ $cat->name }}<span>{{ $cat->blogs_count }}</span></a>
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
                    </div><!-- End .sidebar -->
                </aside><!-- End .col-lg-3 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main><!-- End .main -->
@endsection
