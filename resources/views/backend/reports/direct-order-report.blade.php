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

                    <a href="#" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Export to Excel">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </div>
            </div>





        <div class="row mt-5 align-items-end">

            <div class="col-md-9">
                <form method="POST" action="{{ route('orders-report') }}">
                    @csrf

                    <div class="row">

                        <div class="col-md-4">
                            <label>From Date</label>
                            <input type="date" class="form-control" name="from_date" value="">
                        </div>

                         <div class="col-md-4">
                            <label>To Date</label>
                            <input type="date" class="form-control" name="to_date" value="">
                        </div>

                        <div class="col-md-2 mt-5">
                              <button class="btn btn-primary">  Search </button>
                        </div>

                    </div>


                </form>
            </div>
        </div>






        <div class="row mt-5">

            <table id="datatables" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Order Date </th>
                        <th>Customer Name</th>
                        <th>Shop Name </th>
                        <th>Order Amount</th>

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
                                <td></td>


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
