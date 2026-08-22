 @extends('admin.layouts.app')
 @section('content')
 <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Dashboard v3</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard v3</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!-- Statistics Cards Row -->
            <div class="row mb-4">
              <!-- Total Orders -->
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card h-100 border-0 shadow-sm d-flex flex-row align-items-center p-3 bg-white">
                  <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 50px; height: 50px; min-width: 50px;">
                    <i class="bi bi-list-ul fs-4"></i>
                  </div>
                  <div>
                    <div class="text-muted small fw-semibold text-nowrap">Total Orders</div>
                    <div class="fs-5 fw-bold">{{ $totalOrders }}</div>
                  </div>
                </div>
              </div>
              
              <!-- Total Sales -->
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card h-100 border-0 shadow-sm d-flex flex-row align-items-center p-3 bg-white">
                  <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 50px; height: 50px; min-width: 50px;">
                    <i class="bi bi-currency-dollar fs-4"></i>
                  </div>
                  <div>
                    <div class="text-muted small fw-semibold text-nowrap">Total Sales</div>
                    <div class="fs-5 fw-bold">${{ number_format($totalAmount, 2) }}</div>
                  </div>
                </div>
              </div>

              <!-- Total Customer -->
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card h-100 border-0 shadow-sm d-flex flex-row align-items-center p-3 bg-white">
                  <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 50px; height: 50px; min-width: 50px;">
                    <i class="bi bi-people-fill fs-4"></i>
                  </div>
                  <div>
                    <div class="text-muted small fw-semibold text-nowrap">Total Customer</div>
                    <div class="fs-5 fw-bold">{{ $totalCustomer }}</div>
                  </div>
                </div>
              </div>

              <!-- Total Products -->
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card h-100 border-0 shadow-sm d-flex flex-row align-items-center p-3 bg-white">
                  <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 50px; height: 50px; min-width: 50px;">
                    <i class="bi bi-box-seam fs-4"></i>
                  </div>
                  <div>
                    <div class="text-muted small fw-semibold text-nowrap">Total Products</div>
                    <div class="fs-5 fw-bold">{{ $totalProducts }}</div>
                  </div>
                </div>
              </div>

              <!-- Pending Orders -->
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card h-100 border-0 shadow-sm d-flex flex-row align-items-center p-3 bg-white">
                  <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 50px; height: 50px; min-width: 50px;">
                    <i class="bi bi-clock-history fs-4"></i>
                  </div>
                  <div>
                    <div class="text-muted small fw-semibold text-nowrap">Pending Orders</div>
                    <div class="fs-5 fw-bold">{{ $pendingOrders }}</div>
                  </div>
                </div>
              </div>

              <!-- Contact Messages -->
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card h-100 border-0 shadow-sm d-flex flex-row align-items-center p-3 bg-white">
                  <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 50px; height: 50px; min-width: 50px;">
                    <i class="bi bi-envelope-open fs-4"></i>
                  </div>
                  <div>
                    <div class="text-muted small fw-semibold text-nowrap">Contact Messages</div>
                    <div class="fs-5 fw-bold">{{ $totalContactMessages }}</div>
                  </div>
                </div>
              </div>
            </div>
            <!--begin::Row-->
            <div class="row">
              <div class="col-lg-6">
                <div class="card mb-4">
                  <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                       <h3 class="card-title">Store Activity (Orders)</h3>
                      <a
                        href="javascript:void(0);"
                        class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                        >View Report</a
                      >
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex">
                      <p class="d-flex flex-column">
                        <span class="fw-bold fs-5">{{ array_sum($ordersData) }}</span>
                        <span>Orders Last 7 Days</span>
                      </p>
                      <p class="ms-auto d-flex flex-column text-end">
                        <span class="text-secondary">Store activity tracking</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->
 
                    <div class="position-relative mb-4">
                      <div id="visitors-chart"></div>
                    </div>
 
                    <div class="d-flex flex-row justify-content-end">
                      <span class="me-2">
                        <i class="bi bi-square-fill text-primary"></i> Total Orders
                      </span>
 
                      <span> <i class="bi bi-square-fill text-secondary"></i> Completed Orders </span>
                    </div>
                  </div>
                </div>
                <!-- /.card -->

              </div>
              <!-- /.col-md-6 -->
              <div class="col-lg-6">
                <div class="card mb-4">
                  <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                      <h3 class="card-title">Sales Overview</h3>
                      <a
                        href="javascript:void(0);"
                        class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                        >View Report</a
                      >
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex">
                      <p class="d-flex flex-column">
                        <span class="fw-bold fs-5">${{ number_format(array_sum($salesData), 2) }}</span>
                        <span>Sales Last 6 Months</span>
                      </p>
                      <p class="ms-auto d-flex flex-column text-end">
                        <span class="text-secondary">Sales breakdown metrics</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                      <div id="sales-chart"></div>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                      <span class="me-2">
                        <i class="bi bi-square-fill text-primary"></i> Sales
                      </span>
                      <span class="me-2">
                        <i class="bi bi-square-fill text-success"></i> Shipping
                      </span>
                      <span>
                        <i class="bi bi-square-fill text-warning"></i> Discount
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.col-md-6 -->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
    @endsection

@section('script')
<script>
  // 1. Store Activity (Orders) Line Chart
  const visitors_chart_options = {
    series: [
      {
        name: 'Total Orders',
        data: @json($ordersData),
      },
      {
        name: 'Completed Orders',
        data: @json($completedOrdersData),
      },
    ],
    chart: {
      height: 200,
      type: 'line',
      toolbar: {
        show: false,
      },
    },
    colors: ['#0d6efd', '#adb5bd'],
    stroke: {
      curve: 'smooth',
    },
    grid: {
      borderColor: '#e7e7e7',
      row: {
        colors: ['#f3f3f3', 'transparent'],
        opacity: 0.5,
      },
    },
    legend: {
      show: false,
    },
    markers: {
      size: 1,
    },
    xaxis: {
      categories: @json($visitorsCategories),
    },
  };

  const visitorsChartEl = document.querySelector('#visitors-chart');
  if (visitorsChartEl) {
    const visitors_chart = new ApexCharts(
      visitorsChartEl,
      visitors_chart_options,
    );
    visitors_chart.render();
  }

  // 2. Sales Overview Bar Chart
  const sales_chart_options = {
    series: [
      {
        name: 'Sales',
        data: @json($salesData),
      },
      {
        name: 'Shipping',
        data: @json($shippingData),
      },
      {
        name: 'Discount',
        data: @json($discountData),
      },
    ],
    chart: {
      type: 'bar',
      height: 200,
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '55%',
        endingShape: 'rounded',
      },
    },
    legend: {
      show: false,
    },
    colors: ['#0d6efd', '#20c997', '#ffc107'],
    dataLabels: {
      enabled: false,
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent'],
    },
    xaxis: {
      categories: @json($salesCategories),
    },
    fill: {
      opacity: 1,
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return '$ ' + val.toFixed(2);
        },
      },
    },
  };

  const salesChartEl = document.querySelector('#sales-chart');
  if (salesChartEl) {
    const sales_chart = new ApexCharts(
      salesChartEl,
      sales_chart_options,
    );
    sales_chart.render();
  }
</script>
@endsection