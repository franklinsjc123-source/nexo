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
                                    {{ $order_count ?? 0  }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>


            {{-- <div class="col-md-6 col-xl-4 col-xxl-4">
                <div class="card card-h-100 IntelligenceGradient text-white">
                   <a href="{{ route('orders') }}">
                    <div class="card-body">


                        <div class="d-flex justify-content-between gap-5 mb-5">
                            <div>
                                <h4 class="text-white mb-1">Today Orders</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div
                                    class="h-48px w-48px bg-white fs-5 rounded d-flex justify-content-center align-items-center text-black fw-semibold">
                                   0</div>

                            </div>
                        </div>

                    </div>
                    </a>
                </div>
            </div> --}}




            <div class="col-md-6 col-xl-4 col-xxl-3">
                <div class="card card-h-100 datascienceGradient text-white">
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


            <div class="col-md-6 col-xl-4 col-xxl-3">
                <div class="card card-h-100 webGradient text-white">
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
                <div class="card card-h-100 IntelligenceGradient text-white">
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

        </div>


    </div><!--End container-fluid-->
</main>
<!--End app-wrapper-->
<script>
    var statusCont = '<?php echo isset($monthChart) ? json_encode($monthChart) : 0 ?>';

    if (statusCont.length > 0) {
        var rows = JSON.parse(statusCont);
        var cont = [];
        var sts = [];
        rows.forEach(function(i) {
            cont.push(i.cont);
            sts.push(i.month);
        })

        var options = {
            series: [{
                name:'Request',
                data: cont
            }],
            chart: {
                height: 320,
                type: 'bar',
                toolbar: {
                    show: false
                },
            },
            plotOptions: {
                bar: {
                    columnWidth: '35%',
                    borderRadius: 8,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            xaxis: {
                categories: sts,
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.4,
                    gradientToColors: ['#ffcc80', '#ffe0b2'],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            colors: ['#ffb74d']
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    }


    var pieChartData = '<?php echo isset($pieChart) ? json_encode($pieChart) : 0 ?>';
    if (pieChartData.length > 0) {
        var rows = JSON.parse(pieChartData);
        var cont1 = [];
        var service = [];
        rows.forEach(function(i) {
            cont1.push(parseInt(i.cont));
            service.push(i.service_type);
        })
     var options = {
          series: cont1,
          chart: {
          width: 380,
          type: 'pie',
        },
        labels:service,
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 300,
              height:300
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#sales_managment"), options);
        chart.render();
    }


var locationChart = '<?php echo isset($locationChart) ? json_encode($locationChart) : "[]"; ?>';

if (locationChart !== "[]" && locationChart.length > 2) {

    var rows = JSON.parse(locationChart);
    var locData = [];
    rows.forEach(function(i) {
        locData.push({
            x: i.location,
            y: i.cont
        });
    });
    var options = {
        series: [
            {
                data: locData
            }
        ],
        chart: {
            height: 350,
            type: 'treemap'
        },
        legend: {
            show: false
        },
        title: {
            text: 'Location Request'
        }
    };
    var chart = new ApexCharts(document.querySelector("#location-chart"), options);
    chart.render();
}



</script>
@endsection
