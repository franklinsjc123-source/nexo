 @extends('backend.app_template')
 @section('title','User Store or Update')
 @section('content')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

 <?php

     $order_id         = isset($record->id) ? $record->id : '';
     $shop_id          = isset($record->shop_id) ? $record->shop_id : '';
     $customer_id      = isset($record->customer_id) ? $record->customer_id : '';
     $image_url        = isset($record->image_url) ? $record->image_url : '';
     $type             = ($order_id == '')   ? 'Create' : 'Update';


    ?>
 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Direct Order </a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>





         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="directOrderForm" action="<?= route('storeUpdateDirectOrder') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> Direct Order</h5>
                                 <div class="float-end">
                                     <a href="<?= route('direct-orders') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>

                                        <input type="hidden" name="id" value={{ $order_id  }}  >

                                <div class="card-body">
                                    <div class="card-body row border shadow-lg rounded p-4 m-2">


                                        <center>
                                            <a target="_blank" href="<?=  $image_url  ?>"><img src="<?= $image_url ?>" class="mb-5" height="150" width="150" ></a>
                                        </center>


                                        <div class="section-title bg-light mb-4">
                                            <h6 class="fw-bold text-success mb-0">Add Details</h6>
                                        </div>




                                        <div id="otherChargesWrapper">

                                            {{-- EDIT MODE --}}
                                            @if(!empty($order_items) && count($order_items) > 0)

                                                @foreach($order_items as $oi)
                                                    <div class="row other-charge-row align-items-end mt-3">

                                                        <!-- Description -->
                                                        <div class="col-xl-3">
                                                            <label class="form-label fw-semibold">Product Name</label>
                                                            <input type="text" class="form-control required-field" name="product_name[]" value="{{ $oi->product_name }}" placeholder="Enter Product Name">
                                                        </div>

                                                        <div class="col-xl-2">
                                                            <label class="form-label fw-semibold">HSN Code</label>
                                                            <input type="text" class="form-control" name="hsn_code[]"  maxlength="6"  value="{{ $oi->hsn_code }}" placeholder="Example:  9999" oninput="this.value = this.value.replace(/[^0-9]/g,'');">
                                                        </div>

                                                        <div class="col-xl-2">
                                                            <label class="form-label fw-semibold">Quantity</label>
                                                            <input type="text" class="form-control required-field" name="quantity[]" value="{{ $oi->quantity }}" placeholder="Example:  2 kg">
                                                        </div>

                                                        <!-- Amount -->
                                                        <div class="col-xl-2">
                                                            <label class="form-label fw-semibold">Amount</label>
                                                            <input type="text" class="form-control other-amount required-field" name="amount[]" value="{{ $oi->amount }}" placeholder="Enter Amount" oninput="limitDecimal(this); calculateInvoice();">
                                                        </div>

                                                        <!-- Buttons -->
                                                        <div class="col-xl-2">
                                                            <div class="d-flex gap-2 mt-4">
                                                                <button type="button" class="btn btn-success addRow">+</button>
                                                                <button type="button" class="btn btn-danger removeRow">−</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @endforeach

                                            @else
                                                {{-- ADD MODE --}}
                                                <div class="row other-charge-row align-items-end mt-3">

                                                    <div class="col-xl-4">
                                                        <label class="form-label fw-semibold">Product Name</label>
                                                        <input type="text" class="form-control required-field" name="product_name[]" placeholder="Enter Product Name">
                                                    </div>


                                                        <div class="col-xl-2">
                                                            <label class="form-label fw-semibold">HSN Code</label>
                                                            <input type="text" class="form-control " name="hsn_code[]" value="" placeholder="Example:  9999">
                                                        </div>

                                                        <div class="col-xl-2">
                                                            <label class="form-label fw-semibold">Quantity</label>
                                                            <input type="text" class="form-control required-field" name="quantity[]" value="" placeholder="Example:  2 kg">
                                                        </div>

                                                    <div class="col-xl-2">
                                                        <label class="form-label fw-semibold">Amount</label>
                                                        <input type="text" class="form-control other-amount required-field" name="amount[]" placeholder="Enter Amount" oninput="limitDecimal(this); calculateInvoice();">
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <div class="d-flex gap-2 mt-4">
                                                            <button type="button" class="btn btn-success addRow">+</button>
                                                            <button type="button" class="btn btn-danger removeRow">−</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif

                                        </div>


                                    </div>
                                </div>

                                <div class="card-body">

                                    <div class="card-body row border shadow-lg rounded p-4 ">

                                         <div class="col-xl-8 mt-3">
                                            <label style="float:right; margin-top:7px" class="form-label fw-semibold">Total Amount </label>

                                        </div>

                                         <div class="col-xl-4 mt-3">
                                            <input type="text" class="form-control" id="total_amount" name="total_amount" placeholder=" Amount" value="{{ $record->total_amount ?? '' }}" maxlength="20" readonly>

                                        </div>

                                          <div class="col-xl-8 mt-3">
                                            <label style="float:right; margin-top:7px" class="form-label fw-semibold">CGST </label>

                                        </div>

                                         <div class="col-xl-4 mt-3">
                                            <input type="text" class="form-control" id="cgst" name="cgst" placeholder=" Amount" value="{{ $record->cgst ?? '' }}" maxlength="20" readonly>

                                        </div>



                                        <div class="col-xl-8 mt-3">
                                            <label style="float:right; margin-top:7px" class="form-label fw-semibold">SGST </label>

                                        </div>

                                         <div class="col-xl-4 mt-3">
                                            <input type="text" class="form-control" id="sgst" name="sgst" placeholder=" Amount" value="{{ $record->sgst ?? '' }}" maxlength="20" readonly>

                                        </div>




                                        <div class="col-xl-8 mt-3">
                                            <label style="float:right; margin-top:7px" class="form-label fw-semibold">Advance Amount </label>

                                        </div>

                                         <div class="col-xl-4 mt-3">
                                            <input type="text" class="form-control" id="advance_amount" name="advance_amount" placeholder=" Amount" value="{{ $record->advance_amount ?? '' }}" maxlength="20"  readonly>

                                        </div>

                                          <div class="col-xl-8 mt-3">
                                            <label style="float:right; margin-top:7px" class="form-label fw-semibold">Delivery Amount </label>

                                        </div>

                                         <div class="col-xl-4 mt-3">
                                            <input type="text" class="form-control" id="delivery_amount" required name="delivery_amount" placeholder=" Amount" value="{{ $record->delivery_amount ?? '' }}" maxlength="20" oninput="this.value = this.value.replace(/[^0-9.]/g,''); limitDecimal(this); calculateInvoice();" >

                                        </div>


                                         <div class="col-xl-8 mt-3">
                                            <label style="float:right; margin-top:7px" class="form-label fw-semibold">Total Invoice Amount </label>

                                        </div>

                                         <div class="col-xl-4 mt-3">
                                            <input type="text" class="form-control" id="total_invoice_amount" name="total_invoice_amount" placeholder=" Amount" value="{{ $record->total_invoice_amount ?? '' }}" maxlength="20"  >

                                        </div>


                                    </div>
                                </div>
                        </div>
                    </div>




                    <div class="d-flex justify-content-end gap-3 my-5">
                        <a href="" class="btn btn-light-light text-muted">Cancel</a>
                        <button type="submit" class="btn btn-primary">Generate Invoice</button>
                    </div>

                </form>
             </div>

         </div>
     </div>




 </main>





<script>
    $(document).ready(function () {
        initSelect2();
    });

    function initSelect2() {
        $('.vehicle-select').select2({
            width: '100%'
        });
    }

</script>






 <script>



$(function () {

    $.validator.addClassRules("required-field", {
        required: true
    });

    $("#directOrderForm").validate({
        ignore: [],
        errorElement: "span",
          errorPlacement: function(error, element) {
            return false;
        },

        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        }
    });

});




document.addEventListener('click', function (e) {

    // ADD
    if (e.target.classList.contains('addRow')) {

        let row = e.target.closest('.other-charge-row');
        let clone = row.cloneNode(true);

        let rowCount = document.querySelectorAll('.other-charge-row').length;

        clone.querySelectorAll('input').forEach(input => {

            input.value = '';
            $(input).removeClass('is-invalid error');
            $(input).next('span.text-danger').remove();

            // 🔥 Change name to unique index
            if (input.name.includes('product_name')) {
                input.name = 'product_name[' + rowCount + ']';
            }
            if (input.name.includes('quantity')) {
                input.name = 'quantity[' + rowCount + ']';
            }
            if (input.name.includes('amount')) {
                input.name = 'amount[' + rowCount + ']';
            }
        });

        document.getElementById('otherChargesWrapper').appendChild(clone);

        // Add validation to new fields
        $(clone).find(".required-field").each(function () {
            $(this).rules("add", {
                required: true
            });
        });
    }


    if (e.target.classList.contains('removeRow')) {

        let rows = document.querySelectorAll('.other-charge-row');

        if (rows.length > 1) {
            e.target.closest('.other-charge-row').remove();
            calculateInvoice();
        }
    }
});


  function limitDecimal(el) {
    el.value = el.value.replace(/[^0-9.]/g, '');

    if ((el.value.match(/\./g) || []).length > 1) {
        el.value = el.value.slice(0, -1);
        return;
    }

    if (el.value.includes('.')) {
        let parts = el.value.split('.');
        parts[1] = parts[1].slice(0, 2);
        el.value = parts.join('.');
    }
}
function calculateInvoice() {

    let total_other_amount = 0;

    // Sum all other amounts
    document.querySelectorAll('.other-amount').forEach(el => {
        total_other_amount += parseFloat(el.value) || 0;
    });


    $('#total_amount').val(total_other_amount.toFixed(2));


    let advance_amount = total_other_amount ;

    let cgst = total_other_amount * 0.09;
    let sgst = total_other_amount * 0.09;

    let advance_total = (advance_amount + cgst + sgst )  * 0.30;


    let advanceField = document.getElementById('advance_amount');
    if (advanceField) {
        advanceField.value = advance_total.toFixed(2);
    }


    if (document.getElementById('cgst')) {
        document.getElementById('cgst').value = cgst.toFixed(2);
    }

    if (document.getElementById('sgst')) {
        document.getElementById('sgst').value = sgst.toFixed(2);
    }

    // Get advance & delivery
    let delivery_amount = parseFloat(document.getElementById('delivery_amount')?.value) || 0;

    // Final total calculation
    let total_invoice_amount = total_other_amount + cgst + sgst + delivery_amount ;

    // Set total invoice
    let totalField = document.getElementById('total_invoice_amount');
    if (totalField) {
        totalField.value = total_invoice_amount.toFixed(2);
    }
}
 </script>




 @endsection
