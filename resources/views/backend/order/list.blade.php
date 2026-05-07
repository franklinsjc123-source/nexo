@extends('backend.app_template')
@section('title','Orders  List')
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
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>

            </div>

        </div>

        <div class="row">

            <table id="datatables" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Order Date </th>
                        <th>Order ID </th>
                        <th>Name</th>
                        <th>Order Amount</th>
                        <?php if (Auth::user()->auth_level != 4) { ?>
                            <th>Payment Mode</th>
                            <th>Payment Type</th>
                        <?php } ?>
                        <th>Delivered By</th>
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
                                <td>{{ date('d-m-Y h:i A', strtotime($row->created_at)) }}</td>
                                <td><?php echo $row->order_id ?></td>
                                <td>
                                    <?php if ($row->customerData) { ?>
                                        <?php if (Auth::user()->auth_level != 4) { ?>
                                            <a href="javascript:void(0)" class="viewCustomerDetails" 
                                               data-name="<?= $row->customerData->name ?>" 
                                               data-email="<?= $row->customerData->email ?>" 
                                               data-mobile="<?= $row->customerData->mobile ?>">
                                                <?= $row->customerData->name ?>
                                            </a>
                                        <?php } else { ?>
                                            <?= $row->customerData->name ?>
                                        <?php } ?>
                                    <?php } else { echo '-'; } ?>
                                </td>
                                <td>
                                    <?php
                                      if (Auth::user()->auth_level == 4) {
                                            echo number_format(round($row->final_shop_total), 2);
                                        } else {
                                            echo number_format(round($row->amount + $row->ship_amount), 2);
                                        }
                                    ?>
                                </td>
                                <?php if (Auth::user()->auth_level != 4) { ?>
                                    <td>
                                        <?php
                                            if (strtolower($row->payment_mode) == 'razorpay') {
                                                echo 'ONLINE';
                                            } else {
                                                echo 'COD';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if ($row->order_status == 2) {
                                                echo 'FULL';
                                            } else {
                                                echo $row->payment_type ?? '-';
                                            }
                                        ?>
                                    </td>
                                <?php } ?>

                                <td>
                                    <?php if ($row->deliveryPerson) { ?>
                                        <a href="javascript:void(0)" class="viewDeliveryDetails"
                                           data-name="<?= $row->deliveryPerson->name ?>"
                                           data-email="<?= $row->deliveryPerson->email ?>"
                                           data-mobile="<?= $row->deliveryPerson->mobile ?>">
                                            <?= $row->deliveryPerson->name ?>
                                        </a>
                                    <?php } else { echo '-'; } ?>
                                </td>

                                <td>
                                    <?php

                                        if ($row->order_status == 2) {
                                            $class = "success";
                                            $text  = "Delivered";
                                        } elseif ($row->order_status == 3) {
                                            $class = "danger";
                                            $text  = "Cancelled";
                                        }

                                        elseif ($row->order_status == 4 || $row->is_dispatched == 1 ) {
                                            $class = "secondary";
                                            $text  = "Dispatched";
                                        } else if ($row->order_status == 1) {
                                            $class = "warning";
                                            $text  = "New Order";
                                        } else {
                                            $class = "secondary";
                                            $text  = "Unknown";
                                        }
                                    ?>

                                    <?php if (Auth::user()->auth_level == 4 && ($row->order_status == 2 || $row->order_status == 3)) { ?>
                                        <span class="badge bg-<?php echo $class; ?>"> <?php echo $text; ?> </span>
                                    <?php } else { ?>
                                        <a href="javascript:void(0)"
                                            class="badge bg-<?php echo $class; ?> editOrderStatus" data-id="<?= $row->id ?>" data-status="<?= $row->order_status ?>"> <?php echo $text; ?>
                                        </a>
                                    <?php } ?>

                                </td>

                                <td>

                                    <a href="javascript:void(0)" class="btn btn-sm btn-info viewOrderItems" data-id="<?= $row->id ?>"> <i class="bi bi-eye"></i> </a>

                                    <?php  if(Auth::user()->auth_level  == 4 ) { ?>
                                        <a data-toggle="tooltip" target="_blank" href="{{ $row->invoice_path }}" data-placement="top" title="Invoice"  class="btn btn-sm btn-secondary"><i class="bi bi-file-earmark-break"></i></a>
                                    <?php  } else{ ?>
                                        <a data-toggle="tooltip" target="_blank" href="{{ $row->invoice }}" data-placement="top" title="Invoice"  class="btn btn-sm btn-secondary"><i class="bi bi-file-earmark-break"></i></a>
                                    <?php  }  ?>

                                </td>
                            </tr>

                     <?php $i++;
                                            }?>

                </tbody>
            </table>
        </div>
        <!-- Submit Section -->
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
                            <option value="4">Dispatched</option>
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


<div class="modal fade" id="customerDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Details</h5>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Name:</strong></div>
                    <div class="col-md-8"><span id="customer_name"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Email:</strong></div>
                    <div class="col-md-8"><span id="customer_email"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Mobile:</strong></div>
                    <div class="col-md-8"><span id="customer_mobile"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deliveryDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delivery Person Details</h5>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Name:</strong></div>
                    <div class="col-md-8"><span id="delivery_name"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Email:</strong></div>
                    <div class="col-md-8"><span id="delivery_email"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Mobile:</strong></div>
                    <div class="col-md-8"><span id="delivery_mobile"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="orderItemsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Order Items</h5>
            </div>

            <div class="modal-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <?php if (Auth::user()->auth_level != 4) { ?>
                                <th>Shop</th>
                            <?php } ?>
                            <th>Product</th>
                            <th>Unit</th>
                            <th>Product Price</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                        </tr>
                    </thead>

                    <tbody id="order_items_body">
                    </tbody>

                </table>

            </div>

        </div>
    </div>
</div>


<script>

    $(document).on('click', '#close-modal', function () {

        $('#orderStatusModal').modal('hide');
    });

   $(document).on('click', '.editOrderStatus', function () {

    var auth_level = "{{ auth()->user()->auth_level }}";

    let orderId = $(this).data('id');
    let status  = $(this).data('status');

    $('#order_id').val(orderId);

    // reset options
    $('#change_order_status option').prop('disabled', false);

    // set current status
    $('#change_order_status').val(status).trigger('change');

    // SHOP USER (auth_level = 4)
    if(auth_level == 4){

        // if order already Delivered or Cancelled
        if(status == 2 || status == 3){
            $('#change_order_status option').prop('disabled', true);
        }else{
            // allow only Dispatched
            $('#change_order_status option').prop('disabled', true);
            $('#change_order_status option[value="4"]').prop('disabled', false);
        }

    }

    $('#orderStatusModal').modal('show');

});

    $('#saveStatus').click(function () {

    let orderId = $('#order_id').val();
    let status  = $('#change_order_status').val();

    $.ajax({
        url: "<?= route('orders-status-update') ?>",
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


$(document).on("click", ".viewOrderItems", function () {

    let order_id = $(this).data("id");

    $.ajax({
        url: "/get-order-items",
        type: "POST",
        data: {
            order_id: order_id,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {

            let html = "";
            let i = 1;

            var auth_level = "{{ Auth::user()->auth_level }}";
            response.data.forEach(function(item){

                html += `
                <tr>
                    <td>${i}</td>
                    ${auth_level != 4 ? `<td>${item.shop_data ? item.shop_data.shop_name : '-'}</td>` : ''}
                    <td>${item.product ? item.product.product_name : ''}</td>
                    <td>${item.unit_data ? item.unit_data.unit_name : ''}</td>
                    <td>${item.product_price}</td>
                    <td>${item.qty}</td>
                    <td>${item.price}</td>
                </tr>
                `;

                i++;
            });

            $("#order_items_body").html(html);

            $("#orderItemsModal").modal("show");

        }
    });

});


$(document).on("click", ".viewDeliveryDetails", function () {
    let name = $(this).data("name");
    let email = $(this).data("email");
    let mobile = $(this).data("mobile");

    $("#delivery_name").text(name);
    $("#delivery_email").text(email);
    $("#delivery_mobile").text(mobile);

    $("#deliveryDetailsModal").modal("show");
});


$(document).on("click", ".viewCustomerDetails", function () {
    let name = $(this).data("name");
    let email = $(this).data("email");
    let mobile = $(this).data("mobile");

    $("#customer_name").text(name);
    $("#customer_email").text(email);
    $("#customer_mobile").text(mobile);

    $("#customerDetailsModal").modal("show");
});



</script>
@endsection
