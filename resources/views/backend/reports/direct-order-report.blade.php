@extends('backend.app_template')
@section('title','Direct Order Report')
@section('content')

<style>
    .select2-container {
    width: 100% !important;
}

.select2-container--open {
    z-index: 9999 !important; /* Below modal (1050) */
}
</style>
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="d-flex align-items-center mt-2 mb-2">
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Direct Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>

            </div>

        </div>
        <?php if( Auth::user()->auth_level != 4) { ?>


        <div class="row mt-5 align-items-end">

            <div class="col-md-9">
                <form method="POST" action="{{ route('direct-order-abstract') }}">
                    @csrf

                    <div class="row">

                        <div class="col-md-4 mb-2">
                            <label>Year</label>
                            <select class="form-control select2" name="year" id="yearSelect">
                                @for($y = now()->year; $y >= 2026; $y--)
                                    <option value="{{ $y }}" {{ (request('year') ?? now()->year) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Month</label>
                            <select class="form-control select2" name="month" id="monthSelect">
                                @foreach([
                                    1=>'January',2=>'February',3=>'March',4=>'April',
                                    5=>'May',6=>'June',7=>'July',8=>'August',
                                    9=>'September',10=>'October',11=>'November',12=>'December'
                                ] as $key => $month)
                                    <option value="{{ $key }}" {{ (request('month') ?? now()->month) == $key ? 'selected' : '' }}>
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                          <div class="col-md-4 mb-2">
                            <label>Order Status</label>
                            <select class="form-control select2 " id="order_status" name="order_status">
                                <option value="">All</option>
                                <option {{ request('order_status')  == 1 ? 'selected' : '' }} value="1">New order</option>
                                <option {{ request('order_status')  == 2 ? 'selected' : '' }} value="2">Delivered</option>
                                <option {{ request('order_status')  == 3 ? 'selected' : '' }} value="3">Cancelled</option>
                            </select>
                        </div>

                    </div>

                    <button class="btn btn-primary mt-2">
                        Search
                    </button>
                </form>
            </div>


            @if(request('month'))
                <div class="col-md-3 text-end">
                    <form method="POST" action="{{ route('abstract.download') }}">
                        @csrf
                        <input type="hidden" name="absract_year" value="{{ request('year') }}">
                        <input type="hidden" name="absract_month" value="{{ request('month') }}">

                        <button class="btn btn-success">
                            <i class="bi bi-download"></i> Abstract
                        </button>
                    </form>
                </div>
            @endif

        </div>
        <?php } ?>




        <div class="row mt-5">

            <table id="datatables" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Order Date </th>
                        <th>Customer Name</th>
                        <th>Shop Name </th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                     <?php
                        $i = 0;
                            foreach ($records as $key => $row) {
                            ?>
                            <tr>
                                <td><?php echo $i + 1 ?></td>
                                <td><?= date('d-m-Y', strtotime($row->created_at)) ?></td>
                                <td><?= optional($row->userData)->name ?? '-' ?></td>
                                <td><?= optional($row->shopData)->shop_name ?? '-' ?></td>
                                <td>
                                    <a href="<?= $row->image_url ?>" target="_blank">
                                        <img src="<?= $row->image_url ?>" height="50" width="50">
                                    </a>
                                </td>
                               <td>
                                    <?php
                                        if ($row->order_status == 1) {
                                            $class = "warning";
                                            $text  = "New Order";
                                        } elseif ($row->order_status == 2) {
                                            $class = "success";
                                            $text  = "Delivered";
                                        } elseif ($row->order_status == 3) {
                                            $class = "danger";
                                            $text  = "Cancelled";
                                        } else {
                                            $class = "secondary";
                                            $text  = "Unknown";
                                        }
                                    ?>

                                    <a href="javascript:void(0)"
                                        class="badge bg-<?php echo $class; ?> editOrderStatus" data-id="<?= $row->id ?>" data-status="<?= $row->order_status ?>"> <?php echo $text; ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-warning editOrderStatus" data-id="<?= $row->id ?>" data-status="<?= $row->order_status ?>" data-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    <a href="{{ route('addDirectOrderBill', [$row->id]) }}" class="btn btn-sm btn-info " data-toggle="tooltip" title="View">  <i class="bi bi-eye"></i>
                                    </a>

                                    <?php if($row->total_amount > 0) {  ?>
                                        <a data-toggle="tooltip" target="_blank" href="{{ $row->invoice_file }}" data-placement="top" title="Invoice"  class="btn btn-sm btn-secondary"><i class="bi bi-file-earmark-break"></i></a>
                                    <?php } ?>


                                </td>
                            </tr>

                     <?php $i++; } ?>

                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="orderStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="order_id">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <label class="mb-2">Change Status</label>

                        <select class="form-control select2 " id="change_order_status" name="change_order_status">
                            <option value="1">New order</option>
                            <option value="2">Delivered</option>
                            <option value="3">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer mt-5">

                  <button class="btn btn-danger" id="close-modal" >Cancel</button>
                <button class="btn btn-primary" id="saveStatus">Update</button>
            </div>
        </div>
    </div>
</div>



<script>

    $(document).ready(function () {

    $('#change_order_status').select2({
        dropdownParent: $('#orderStatusModal'),
        width: '100%'
    });

});




$(document).on('click', '#close-modal', function () {

    $('#orderStatusModal').modal('hide');
});

$(document).on('click', '.editOrderStatus', function () {

    let orderId = $(this).data('id');
    let status  = $(this).data('status');

    $('#order_id').val(orderId);

    $('#orderStatusModal').modal('show');

    setTimeout(function(){
        $('#change_order_status')
            .val(status)
            .trigger('change');
    }, 200);

});

$('#saveStatus').click(function () {

    let orderId = $('#order_id').val();
    let status  = $('#change_order_status').val();

    $.ajax({
        url: "<?= route('direct-orders-status-update') ?>",
        type: "POST",
        data: {
            _token: "<?= csrf_token() ?>",
            order_id: orderId,
            status: status
        },
        success: function (res) {
            if (res.status) {
                $('#orderStatusModal').modal('hide');
                location.reload();
            }
        }
    });
});

</script>
@endsection
