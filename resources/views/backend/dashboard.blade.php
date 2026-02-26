@extends('backend.app_template')
@section('title','Dashboard')
@section('content')
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="row mt-5">
            <div class="col-md-6 col-xl-4 col-xxl-4">
                <div class="card card-h-100 webGradient text-white">
                     <a href="{{ route('orders') }}">
                    <div class="card-body">

                        <div class="d-flex justify-content-between gap-5 mb-5">
                            <div>
                                <h4 class="text-white mb-1">Total Orders</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div
                                    class="h-48px w-48px bg-white fs-5 rounded d-flex justify-content-center align-items-center text-black fw-semibold">{{ $order_count ?? 0  }}</div>
                            </div>
                        </div>

                    </div>
                     </a>
                </div>
            </div>

            <div class="col-md-6 col-xl-4 col-xxl-4">
                <div class="card card-h-100 IntelligenceGradient text-white">
                    <a href="{{ route('direct-orders') }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between gap-5 mb-5">
                                <div>
                                    <h4 class="text-white mb-1">Total Direct Orders</h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-48px w-48px bg-white fs-5 rounded d-flex justify-content-center align-items-center text-black fw-semibold">
                                    {{ $direct_order_count ?? 0  }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-xl-4 col-xxl-4">
                <div class="card card-h-100 datascienceGradient text-white">
                     <a href="{{ route('orders') }}">
                    <div class="card-body">

                        <div class="d-flex justify-content-between gap-5 mb-5">
                            <div>
                                <h4 class="text-white mb-1">Today Orders</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div
                                    class="h-48px w-48px bg-white fs-5 rounded d-flex justify-content-center align-items-center text-black fw-semibold"> {{ $today_order_count  ?? 0 }} </div>
                            </div>
                        </div>

                    </div>
                      </a>
                </div>
            </div>

            <div class="col-md-6 col-xl-4 col-xxl-4">
                <div class="card card-h-100 webGradient text-white">
                     <a href="{{ route('direct-orders') }}">
                    <div class="card-body">

                        <div class="d-flex justify-content-between gap-5 mb-5">
                            <div>
                                <h4 class="text-white mb-1">Today  Direct Orders </h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div
                                    class="h-48px w-48px bg-white fs-5 rounded d-flex justify-content-center align-items-center text-black fw-semibold">{{ $today_direct_order_count ?? 0  }}</div>
                            </div>
                        </div>

                    </div>
                     </a>
                </div>
            </div>

            <?php  if (Auth::user()->auth_level != 4) { ?>


                <div class="col-md-6 col-xl-4 col-xxl-4">
                    <div class="card card-h-100 IntelligenceGradient text-white">
                        <a href="{{ route('shop') }}">
                        <div class="card-body">

                            <div class="d-flex justify-content-between gap-5 mb-5">
                                <div>
                                    <h4 class="text-white mb-1">Total Shops</h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-48px w-48px bg-white fs-5 rounded d-flex justify-content-center align-items-center text-black fw-semibold"> {{ $shop_count  ?? 0 }} </div>
                                </div>
                            </div>

                        </div>
                        </a>
                    </div>
                </div>


                <div class="col-md-6 col-xl-4 col-xxl-4">
                    <div class="card card-h-100 datascienceGradient text-white">
                    <a href="{{ route('customers') }}">
                        <div class="card-body">

                            <div class="d-flex justify-content-between gap-5 mb-5">
                                <div>
                                    <h4 class="text-white mb-1">Total Customers</h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-48px w-48px bg-white fs-5 rounded d-flex justify-content-center align-items-center text-black fw-semibold">{{ $customer_count ?? 0 }}</div>
                                </div>
                            </div>

                        </div>
                        </a>
                    </div>
                </div>


                <div class="col-md-6 col-xl-4 col-xxl-4">
                    <div class="card card-h-100 webGradient text-white">
                        <a href="{{ route('deliveryPerson') }}">
                        <div class="card-body">

                            <div class="d-flex justify-content-between gap-5 mb-5">
                                <div>
                                    <h4 class="text-white mb-1">Delivery Persons</h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-48px w-48px bg-white fs-5 rounded d-flex justify-content-center align-items-center text-black fw-semibold">
                                    {{ $delivert_person_count ?? 0 }}</div>

                                </div>
                            </div>

                        </div>
                        </a>
                    </div>
                </div>

            <?php  } ?>


        </div>



        <div class="card mt-4">
            <div class="card-header">
                <h5>Shops Based on Category</h5>
            </div>
            <div class="card-body text-center">

                <!-- Control Size Here -->
                <div style="width:300px; height:300px; margin:auto;">
                    <canvas id="categoryPieChart"></canvas>
                </div>

            </div>
        </div>


    </div><!--End container-fluid-->
</main>
<!--End app-wrapper-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
var ctx = document.getElementById('categoryPieChart').getContext('2d');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($categoryLabels) !!},
        datasets: [{
            data: {!! json_encode($categoryCounts) !!},
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40',
                '#8BC34A',
                '#FF5722'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endsection
