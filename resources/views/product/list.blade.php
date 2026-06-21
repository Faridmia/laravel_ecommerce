
@extends('layouts.app')
@section('style')
	<link rel="stylesheet" href="{{ asset('assets/css/plugins/nouislider/nouislider.css') }}">

	<style type="text/css">
		.active-color {
			
			border: 3px solid #000 !important;
		}
	</style>
@endsection
    @section('content')
        
        <main class="main">
        	<div class="page-header text-center" style="background-image: url('{{url('')}}/assets/images/page-header-bg.jpg')">
        		<div class="container">
                    @if( !empty($getSubCategory) )
        			    <h1 class="page-title">{{ $getSubCategory->name }}</h1>
                    @else
        			<h1 class="page-title">{{ $getCategory->name }}</h1>
                    @endif
        		</div><!-- End .container -->
        	</div><!-- End .page-header -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav mb-2">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Shop</a></li>
                        @if( !empty($getSubCategory) )
                        <li class="breadcrumb-item" aria-current="page"><a href="{{ url($getCategory->category_slug) }}">{{ $getCategory->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $getSubCategory->name }}</li>
                        @else
                        <li class="breadcrumb-item active" aria-current="page">{{ $getCategory->name }}</li>
                        @endif
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="page-content">
                <div class="container">
                	<div class="row">
                		<div class="col-lg-9">
                			<div class="toolbox">
                				<div class="toolbox-left">
                					<div class="toolbox-info">
                						Showing <span>9 of 56</span> Products
                					</div><!-- End .toolbox-info -->
                				</div><!-- End .toolbox-left -->

                				<div class="toolbox-right">
                					<div class="toolbox-sort">
                						<label for="sortby">Sort by:</label>
                						<div class="select-custom">
											<select name="sortby" id="sortby" class="form-control changesortby">
												<option value="">Select</option>
												<option value="popularity">Most Popular</option>
												<option value="rating">Most Rated</option>
												<option value="date">Date</option>
											</select>
										</div>
                					</div><!-- End .toolbox-sort -->
                					
                				</div><!-- End .toolbox-right -->
                			</div><!-- End .toolbox -->
						<div id="getProductAjax">
                        @include('product._list');
						</div>
                		</div><!-- End .col-lg-9 -->
                		<aside class="col-lg-3 order-lg-first">
							<form id="filterForm" method="post" action="">
								{{	csrf_field() }}
								<input type="hidden" name="sub_category_id" id="sub_category_id">
								<input type="hidden" name="sub_brand_id" id="sub_brand_id">
								<input type="hidden" name="color_id" id="get_color_id">
								<input type="hidden" name="sort_by_id" id="get_sort_by_id">
							</form>
                			<div class="sidebar sidebar-shop">
                				<div class="widget widget-clean">
                					<label>Filters:</label>
                					<a href="#" class="sidebar-filter-clear">Clean All</a>
                				</div><!-- End .widget widget-clean -->

                				<div class="widget widget-collapsible">
    								<h3 class="widget-title">
									    <a data-toggle="collapse" href="#widget-1" role="button" aria-expanded="true" aria-controls="widget-1">
									        Category
									    </a>
									</h3><!-- End .widget-title -->

									<div class="collapse show" id="widget-1">
										<div class="widget-body">
											<div class="filter-items filter-items-count">
												@foreach($getSubCategoryFilter as $fcategory)
												<div class="filter-item">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input changecategory" id="cat-{{ $fcategory->id }}" value="{{ $fcategory->id }}">
														<label class="custom-control-label" for="cat-{{ $fcategory->id }}">{{ $fcategory->name }}</label>
													</div>
													<span class="item-count">({{ $fcategory->totalProducts() }})</span>
												</div>
												@endforeach

												
											</div><!-- End .filter-items -->
										</div><!-- End .widget-body -->
									</div><!-- End .collapse -->
        						</div><!-- End .widget -->

        						

        						<div class="widget widget-collapsible">
    								<h3 class="widget-title">
									    <a data-toggle="collapse" href="#widget-3" role="button" aria-expanded="true" aria-controls="widget-3">
									        Colour
									    </a>
									</h3><!-- End .widget-title -->

									<div class="collapse show" id="widget-3">
										<div class="widget-body">
											<div class="filter-colors">
												@foreach($getColor as $color)
												<a href="javascript:;" data-val="0" id="{{ $color->color_id }}" style="background: {{ $color->code }};" class="changecolor"><span class="sr-only">{{ $color->name }}</span></a>
												@endforeach
												
												
											</div><!-- End .filter-colors -->
										</div><!-- End .widget-body -->
									</div><!-- End .collapse -->
        						</div><!-- End .widget -->

        						<div class="widget widget-collapsible">
    								<h3 class="widget-title">
									    <a data-toggle="collapse" href="#widget-4" role="button" aria-expanded="true" aria-controls="widget-4">
									        Brand
									    </a>
									</h3><!-- End .widget-title -->

									<div class="collapse show" id="widget-4">
										<div class="widget-body">
											<div class="filter-items">
												@foreach($getBrand as $brand)
												<div class="filter-item">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input changebrand" id="brand-{{ $brand->brand_id }}" value="{{ $brand->brand_id }}">
														<label class="custom-control-label" for="brand-{{ $brand->brand_id }}">{{ $brand->name }}</label>
													</div>
												</div>
												@endforeach

											</div><!-- End .filter-items -->
										</div><!-- End .widget-body -->
									</div><!-- End .collapse -->
        						</div><!-- End .widget -->

        						<div class="widget widget-collapsible">
    								<h3 class="widget-title">
									    <a data-toggle="collapse" href="#widget-5" role="button" aria-expanded="true" aria-controls="widget-5">
									        Price
									    </a>
									</h3><!-- End .widget-title -->

									<div class="collapse show" id="widget-5">
										<div class="widget-body">
                                            <div class="filter-price">
                                                <div class="filter-price-text">
                                                    Price Range:
                                                    <span id="filter-price-range"></span>
                                                </div><!-- End .filter-price-text -->

                                                <div id="price-slider"></div><!-- End #price-slider -->
                                            </div><!-- End .filter-price -->
										</div><!-- End .widget-body -->
									</div><!-- End .collapse -->
        						</div><!-- End .widget -->
                			</div><!-- End .sidebar sidebar-shop -->
                		</aside><!-- End .col-lg-3 -->
                	</div><!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End .page-content -->
        </main><!-- End .main -->
        
@endsection

@section('script')
	<script src="{{ asset('assets/js/nouislider.min.js') }}"></script>
	<script>

		

		$('.changesortby').change(function(){
			var ids = '';
			var id = $(this).val();
			$('#get_sort_by_id').val(id);

			filterForm();
		});

		$('.changecategory').change(function(){
			var ids = '';
			var category_id = $(this).val();
			$('.changecategory').each(function(){
				
				if( this.checked ){
					let id = $(this).val();
					console.log(id);
					ids += id + ',';
				}
			});

			$('#sub_category_id').val(ids);

			filterForm();
		});



		$('.changebrand').change(function(){
			var ids = '';
			var category_id = $(this).val();
			$('.changebrand').each(function(){
				
				if( this.checked ){
					let id = $(this).val();
					console.log(id);
					ids += id + ',';
				}
			});

			$('#sub_brand_id').val(ids);

			filterForm();
		});

		// Initialize the brand filter
		$('.changebrand').change();

		$('.changecolor').click(function(){
			var id = $(this).attr('id');
			let status = $(this).data('val');
			
			if( status == 0 ){
				$(this).addClass('active-color');
				$(this).data('val', 1);
			} else {
				$(this).removeClass('active-color');
				$(this).data('val', 0);
			}

			var ids = '';
	
			$('.changecolor').each(function(){
				let status = $(this).data('val');
				if( status == 1 ){
					var id = $(this).attr('id');
					ids += id + ',';
				}
			});

			$('#get_color_id').val(ids);

			filterForm();
		});

		function filterForm( argument ){
			$.ajax({
				type: 'POST',
				url: '{{ url('get_filter_products_ajax') }}',
				data: $('#filterForm').serialize(),
				dataType: 'json',
				success: function(response){
					$('#getProductAjax').html(response.success);
				},
				error: function(){
					
				}
			});
		}
	</script>
@endsection