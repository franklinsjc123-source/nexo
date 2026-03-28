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

            <div class="d-flex justify-content-between align-items-center mb-2">

                <h6 class="mb-0 flex-grow-1"></h6>

                <div class="d-flex gap-2">

                     <a href="javascript:void(0)"
                    class="btn btn-success btn-sm excelBtn"
                    data-table="direct_order_report"
                    title="Export to Excel">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                </a>

                <a href="javascript:void(0)"
                    class="btn btn-success btn-sm pdfBtn"
                    data-table="direct_order_report"
                    title="Export to PDF">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
                </div>
            </div>





        <div class="card mt-4 shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('direct-orders-report') }}">
                    @csrf
                    <div class="row align-items-end g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">From Date</label>
                            <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">To Date</label>
                            <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                        </div>

                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-search me-1"></i> Search
                                </button>
                                <a href="{{ route('direct-orders-report') }}" class="btn btn-light px-4">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>






        <div class="row mt-5">

            <table id="direct_order_report" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Order Date </th>
                        <th>Customer Name</th>
                        <th>Shop Name </th>
                        <th>Image</th>
                        <th>Status</th>

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
                                        } elseif ($row->order_status == 4) {
                                            $class = "secondary";
                                            $text  = "Dispatched";
                                        } else {
                                            $class = "secondary";
                                            $text  = "Unknown";
                                        }
                                    ?>

                                    <a href="javascript:void(0)"
                                        class="badge bg-<?php echo $class; ?>" data-id="<?= $row->id ?>" data-status="<?= $row->order_status ?>"> <?php echo $text; ?>
                                    </a>
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
