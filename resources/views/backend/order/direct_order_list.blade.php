@extends('backend.app_template')
@section('title','Direct Orders  List')
@section('content')
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


        <div class="row mt-5 align-items-end">

            <div class="col-md-9">
                <form method="POST" action="{{ route('direct-order-abstract') }}">
                    @csrf

                    <div class="row">

                        <div class="col-md-4 mb-2">
                            <label>Year</label>
                            <select class="form-control select2" name="year" id="yearSelect">
                                @for($y = now()->year; $y >= 2025; $y--)
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
                        <input type="hidden" name="absract_company" value="{{ request('company') }}">
                        <input type="hidden" name="absract_year" value="{{ request('year') }}">
                        <input type="hidden" name="absract_month" value="{{ request('month') }}">

                        <button class="btn btn-success">
                            <i class="bi bi-download"></i> Abstract
                        </button>
                    </form>
                </div>
            @endif

</div>



        <div class="row">

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
                                    <a data-placement="top" title="Status" data-original-title="Status" href="javascript:void(0)" class="badge bg-pill bg-<?php echo ($row->status == 1) ? 'success' : 'danger' ?>">
                                            <?php echo ($row->status == 1) ? '' : 'New Order' ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-warning " data-id="<?= $row->id ?>" data-status="<?= $row->status ?>" data-toggle="tooltip" title="Edit">  <i class="bi bi-pencil-fill"></i>
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
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="orderStatusModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="orderStatusModalBody">

                <div class="container-fluid ">
            <form method="POST" id="orderStatusForm" id="orderStatusForm"
                action="{{ route('direct-orders-status-update') }}">
                @csrf


        <div class="attendance-form">



                                <div class="row align-items-center mb-3">
                                    <label class="col-12 col-md-4 fw-semibold text-start text-md-end mb-1 mb-md-0">
                                        Staff Code <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-7">
                                        <select class="form-control select2" name="staff_code" id="staff_code"  data-placeholder="" >
                                                <option value="">-- Select --</option>
                                               <option value="1">New order</option>
                                                <option value="2">Delivered</option>
                                                <option value="3">Cancelled</option>
                                            </select>
                                    </div>
                                </div>



    </div>

    <div class="d-flex justify-content-end gap-3 my-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        {{-- <button type="submit" class="btn btn-primary">Save</button> --}}
        <button type="submit" class="btn btn-primary" name="submit_and_next" value="1" > Save </button>
    </div>
</form>
</div>

            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="orderStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="order_id">

                <label>Status</label>


                <select class="form-control select2" id="order_status" name="order_status">
                    <option value="1">New order</option>
                    <option value="2">Delivered</option>
                    <option value="3">Cancelled</option>

                </select>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="saveStatus">Update</button>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).on('click', '.editOrder', function () {
    let orderId = $(this).data('id');
    let status  = $(this).data('status');

    $('#order_id').val(orderId);
    $('#order_status').val(status);

    $('#orderStatusModal').modal('show');
});



$('#saveStatus').click(function () {
    let orderId = $('#order_id').val();
    let status  = $('#order_status').val();

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
