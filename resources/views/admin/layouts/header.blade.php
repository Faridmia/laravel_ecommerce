<!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            

            <!--begin::Messages Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-chat-text"></i>
                <span class="navbar-badge badge text-bg-danger">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="{{ asset('assets/img/user1-128x128.jpg') }}"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Brad Diesel
                        <span class="float-end fs-7 text-danger"
                          ><i class="bi bi-star-fill"></i
                        ></span>
                      </h3>
                      <p class="fs-7">Call me whenever you can...</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="{{ asset('assets/img/user8-128x128.jpg') }}"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        John Pierce
                        <span class="float-end fs-7 text-secondary">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      </h3>
                      <p class="fs-7">I got your message bro</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="{{ asset('assets/img/user3-128x128.jpg') }}"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Nora Silvester
                        <span class="float-end fs-7 text-warning">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      </h3>
                      <p class="fs-7">The subject goes here</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
              </div>
            </li>
            <!--end::Messages Dropdown Menu-->

            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                @if(isset($unreadCount) && $unreadCount > 0)
                  <span class="navbar-badge badge text-bg-warning">{{ $unreadCount }}</span>
                @endif
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end" style="max-height: 400px; overflow-y: auto;">
                <span class="dropdown-item dropdown-header">{{ isset($unreadCount) ? $unreadCount : 0 }} Unread Notifications</span>
                @if(isset($notifications))
                  @forelse($notifications as $notification)
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('notifications.read', $notification->id) }}" class="dropdown-item" style="white-space: normal; @if(!$notification->is_read) font-weight: bold; background-color: #f8f9fa; @endif">
                      <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                        <div class="flex-grow-1">
                          <p class="mb-0 fs-7 text-dark">{{ $notification->message }}</p>
                          <span class="text-secondary fs-8"><i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                      </div>
                    </a>
                  @empty
                    <div class="dropdown-divider"></div>
                    <span class="dropdown-item text-center text-muted fs-7">No notifications</span>
                  @endforelse
                @endif
                <div class="dropdown-divider"></div>
                <a href="javascript:void(0)" class="dropdown-item dropdown-footer"> See All Notifications </a>
              </div>
            </li>
            <!--end::Notifications Dropdown Menu-->

          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="javascript:void(0)" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="{{ asset('assets/img/AdminLTELogo.png') }}"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Ecommerce</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                aria-label="Main navigation"
                data-accordion="false"
              id="navigation">
              <li class="nav-item">
                  <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-speedometer"></i>
                      <p>Dashboard</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="{{ route('admin.admin.list') }}"
                    class="nav-link {{ request()->segment(2) == 'admin' && request()->segment(3) == 'list' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-person"></i>
                      <p>Admin</p>
                  </a>
              </li>
              <li class="nav-item {{ in_array(request()->segment(2), ['product', 'category', 'subcategory', 'brand', 'color']) ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ in_array(request()->segment(2), ['product', 'category', 'subcategory', 'brand', 'color']) ? 'active' : '' }}">
                      <i class="nav-icon bi bi-box-seam"></i>
                      <p>
                          Products
                          <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('admin.product.list') }}"
                            class="nav-link {{ request()->segment(2) == 'product' ? 'active' : '' }}">
                              <i class="nav-icon bi bi-dot"></i>
                              <p>Products</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('admin.category.list') }}"
                            class="nav-link {{ request()->segment(2) == 'category' ? 'active' : '' }}">
                              <i class="nav-icon bi bi-dot"></i>
                              <p>Category</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('admin.subcategory.list') }}"
                            class="nav-link {{ request()->segment(2) == 'subcategory' ? 'active' : '' }}">
                              <i class="nav-icon bi bi-dot"></i>
                              <p>Sub Category</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('admin.brand.list') }}"
                            class="nav-link {{ request()->segment(2) == 'brand' ? 'active' : '' }}">
                              <i class="nav-icon bi bi-dot"></i>
                              <p>Brand</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('admin.color.list') }}"
                            class="nav-link {{ request()->segment(2) == 'color' ? 'active' : '' }}">
                              <i class="nav-icon bi bi-dot"></i>
                              <p>Color</p>
                          </a>
                      </li>
                  </ul>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.coupon.list') }}"
                    class="nav-link {{ request()->segment(2) == 'coupon' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-ticket-perforated"></i>
                      <p>Coupons</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.shipping.zones.list') }}"
                    class="nav-link {{ request()->segment(2) == 'shipping' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-truck"></i>
                      <p>Shipping Zones</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.orders.list') }}"
                    class="nav-link {{ request()->segment(2) == 'orders' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-cart-fill"></i>
                      <p>Orders</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.reviews.list') }}"
                    class="nav-link {{ request()->segment(2) == 'reviews' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-star"></i>
                      <p>Product Reviews</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.page.list') }}"
                    class="nav-link {{ request()->segment(2) == 'page' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-file-earmark-text"></i>
                      <p>CMS Pages</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.slider.list') }}"
                    class="nav-link {{ request()->segment(2) == 'slider' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-images"></i>
                      <p>Slider</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.partner.list') }}"
                    class="nav-link {{ request()->segment(2) == 'partner' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-award"></i>
                      <p>Partners</p>
                  </a>
              </li>
              <li class="nav-item {{ in_array(request()->segment(2), ['blog', 'blog-category', 'blog-comment']) ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ in_array(request()->segment(2), ['blog', 'blog-category', 'blog-comment']) ? 'active' : '' }}">
                      <i class="nav-icon bi bi-journal-text"></i>
                      <p>
                          Blog
                          <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('admin.blog.list') }}"
                            class="nav-link {{ request()->segment(2) == 'blog' ? 'active' : '' }}">
                              <i class="nav-icon bi bi-dot"></i>
                              <p>Blog List</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('admin.blog_category.list') }}"
                            class="nav-link {{ request()->segment(2) == 'blog-category' ? 'active' : '' }}">
                              <i class="nav-icon bi bi-dot"></i>
                              <p>Blog Category</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('admin.blog_comment.list') }}"
                            class="nav-link {{ request()->segment(2) == 'blog-comment' ? 'active' : '' }}">
                              <i class="nav-icon bi bi-dot"></i>
                              <p>Comments</p>
                          </a>
                      </li>
                  </ul>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.team.list') }}"
                    class="nav-link {{ request()->segment(2) == 'team' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-people-fill"></i>
                      <p>Team Members</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.testimonial.list') }}"
                    class="nav-link {{ request()->segment(2) == 'testimonial' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-chat-quote-fill"></i>
                      <p>Testimonials</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.profile') }}"
                    class="nav-link {{ request()->segment(2) == 'profile' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-person-fill-gear"></i>
                      <p>Profile Settings</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.customer.list') }}"
                    class="nav-link {{ request()->segment(2) == 'customer' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-people"></i>
                      <p>Customers</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.contact.list') }}"
                    class="nav-link {{ request()->segment(2) == 'contact' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-envelope-open"></i>
                      <p>Contact Us</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.home_setting') }}"
                    class="nav-link {{ request()->segment(2) == 'home-setting' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-house-gear-fill"></i>
                      <p>Home Setting</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.system_settings') }}"
                    class="nav-link {{ request()->segment(2) == 'system-settings' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-sliders"></i>
                      <p>System Setting</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.smtp_settings') }}"
                    class="nav-link {{ request()->segment(2) == 'smtp-settings' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-envelope-check"></i>
                      <p>SMTP Setting</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.payment_gateways') }}"
                    class="nav-link {{ request()->segment(2) == 'payment-gateways' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-credit-card"></i>
                      <p>Payment Gateways</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.settings') }}"
                    class="nav-link {{ request()->segment(2) == 'settings' ? 'active' : '' }}">
                      <i class="nav-icon bi bi-gear"></i>
                      <p>Checkout Settings</p>
                  </a>
              </li>
              <li class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="nav-link btn btn-link">
                      <i class="nav-icon bi bi-box-arrow-right"></i> Logout
                  </button>
              </form>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->