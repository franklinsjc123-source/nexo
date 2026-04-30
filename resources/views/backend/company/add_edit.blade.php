 @extends('backend.app_template')
 @section('title','Company Store or Update')
 @section('content')
 <?php


    $id                     = isset($record->id) ? $record->id : '';
    $company_name           = isset($record->company_name) ? $record->company_name : '';
    $phone                  = isset($record->phone) ? $record->phone : '';
    $email                  = isset($record->email) ? $record->email : '';
    $delivery_charge        = isset($record->delivery_charge) ? $record->delivery_charge : '';
    $company_address        = isset($record->company_address) ? $record->company_address : '';
    $state                  = isset($record->state) ? $record->state : '';
    $pincode                = isset($record->pincode) ? $record->pincode : '';
    $fssai_no               = isset($record->fssai_no) ? $record->fssai_no : '';
    $gst_no                 = isset($record->gst_no) ? $record->gst_no : '';
    $company_logo           = isset($record->logo) ? $record->logo : '';
    $old_company_logo       = isset($record->logo) ? $record->logo : '';
    $terms                  = isset($record->terms) ? $record->terms : '';
    $invoice_no             = isset($record->invoice_no) ? $record->invoice_no : '';
    $direct_invoice_no      = isset($record->direct_invoice_no) ? $record->direct_invoice_no : '';
    $free_delivery_checkbox = isset($record->free_delivery_checkbox) ? $record->free_delivery_checkbox : '';
    $free_delivery_reason   = isset($record->free_delivery_reason) ? $record->free_delivery_reason : '';
    $message                = isset($record->message) ? $record->message : '';

    $bank_name              = isset($record->bank_name) ? $record->bank_name : '';
    $branch_name            = isset($record->branch_name) ? $record->branch_name : '';
    $ifsc                   = isset($record->ifsc) ? $record->ifsc : '';
    $account_no             = isset($record->account_no) ? $record->account_no : '';
    $upi_id                 = isset($record->upi_id) ? $record->upi_id : '';
    $qr_code                = isset($record->qr_code) ? $record->qr_code : '';
    $old_qr_code            = isset($record->qr_code) ? $record->qr_code : '';
    $type                   = ($id == '')   ? 'Create' : 'Update';

    ?>

 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Company</a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="companyForm" action="<?= route('storeUpdateCompany') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                         <div class="card">
                             <span></span>
                             <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $type }} Company</h5>
                                <a href="<?= route('company') ?>" class="btn btn-primary">Back</a>
                            </div>

                             <input type="hidden" name="id" value="<?= $id ?>" />
                             <div class="card-body">

                                <div class="card-body border shadow-lg rounded p-4 m-2">

                                    <div class="section-title bg-light mb-4">
                                        <h6 class="fw-bold text-success mb-0">Basic Details</h6>
                                    </div>
                                    <div class="row g-3">




                                        <div class="col-xl-4">
                                            <label for="company_name" class="form-label">Company Name <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo $company_name ?>" class="form-control" id="company_name" name="company_name" placeholder="Enter Company Name">
                                            @error('company_name') <span class="text-danger">{{$message}}</span> @enderror

                                        </div>

                                        <div class="col-xl-4">
                                            <label for="company_address" class="form-label">Address  <span class="text-danger"> *</span> </label>
                                            <textarea class="form-control" id="company_address" name="company_address">  <?php echo $company_address ?></textarea>
                                            @error('company_address') <span class="text-danger">{{$message}}</span> @enderror

                                        </div>
                                        <div class="col-xl-4">
                                            <label for="state" class="form-label">State  <span class="text-danger"> *</span> </label>
                                            <input type="text" value="<?php echo $state ?>" class="form-control" id="state" name="state"   placeholder="Enter State">
                                        </div>

                                         <div class="col-xl-4">
                                            <label for="phone" class="form-label">Phone   <span class="text-danger"> *</span></label>
                                            <input type="number" value="<?php echo $phone ?>" class="form-control" id="phone" name="phone"  placeholder="Enter Phone No">
                                        </div>


                                        <div class="col-xl-4">
                                            <label for="email" class="form-label">Email  <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo $email ?>" class="form-control" id="email" name="email"  placeholder="Enter Email">
                                        </div>


                                          <div class="col-xl-4">
                                            <label for="email" class="form-label">Delivery Charge  <span class="text-danger"> *</span></label>
                                            <input type="number" value="<?php echo $delivery_charge ?>" class="form-control" id="delivery_charge" name="delivery_charge"  placeholder="Enter Delivery Charge">
                                        </div>

                                           <div class="col-xl-4">
                                            <label for="gst_no" class="form-label">GST No </label>
                                            <input type="text" value="<?php echo $gst_no ?>" class="form-control" id="gst_no" name="gst_no" placeholder="Enter GST No" >
                                        </div>

                                         <div class="col-xl-4">
                                            <label for="fssai_no" class="form-label">FSSAI No  <span class="text-danger"> *</span> </label>
                                            <input type="text" value="<?php echo $fssai_no ?>" class="form-control" id="fssai_no" name="fssai_no"  placeholder="Enter FSSAI No">
                                        </div>


                                        <div class="col-xl-4">
                                            <label for="terms" class="form-label">Terms & conditions  <span class="text-danger"> </span> </label>
                                            <textarea class="form-control" id="terms" name="terms">  <?php echo $terms ?></textarea>
                                            @error('terms') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="message" class="form-label">Message  <span class="text-danger"> </span> </label>
                                            <textarea class="form-control" id="message" name="message">  <?php echo $message ?></textarea>
                                            @error('message') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>

                                      <div class="col-xl-4 mt-3">
                                            <label for="free_delivery_checkbox" class="form-label"> Free Delivery <span class="text-danger"> </span> </label>

                                            <div class="form-check">
                                                   <label class="form-check-label" for="free_delivery_checkbox">
                                                    Enable
                                                </label>
                                                <input class="form-check-input" type="checkbox"
                                                    id="free_delivery_checkbox"
                                                    name="free_delivery"
                                                    value="1" <?= $free_delivery_checkbox == 1 ?  'checked' : ''; ?>>

                                            </div>
                                        </div>
                                       <div class="col-xl-4 mt-3" id="free_delivery_reason_div"
                                            style="<?php echo !empty($free_delivery_reason) ? 'display:block;' : 'display:none;'; ?>">

                                            <label class="form-label">
                                                Free Delivery Reason <span class="text-danger">*</span>
                                            </label>

                                            <textarea class="form-control"
                                                    id="free_delivery_reason"
                                                    name="free_delivery_reason"
                                                    placeholder="Enter Free Delivery Reason"><?php echo $free_delivery_reason ?? ''; ?></textarea>
                                        </div>




                                        <div class="col-xl-4">
                                            <label for="invoice_no" class="form-label">Invoice  No  <span class="text-danger"> *</span> </label>
                                            <input type="text" value="<?php echo $invoice_no ?>" class="form-control" id="invoice_no" name="invoice_no"  placeholder="Enter Invoice No">
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="direct_invoice_no" class="form-label">Invoice No (Direct) <span class="text-danger"> *</span> </label>
                                            <input type="text" value="<?php echo $direct_invoice_no ?>" class="form-control" id="direct_invoice_no" name="direct_invoice_no"  placeholder="Enter Invoice No (Direct)">
                                        </div>











                                        {{-- <div class="col-xl-4">
                                            <label for="pincode" class="form-label">Pincode  <span class="text-danger"> *</span> </label>
                                            <input type="text" value="<?php echo $pincode ?>" class="form-control" id="pincode" name="pincode" maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g,'');" placeholder="Enter Pincode">
                                        </div> --}}


                                        <div class="col-xl-4">
                                            <label for="company_logo" class="form-label">Company Logo  <span class="text-danger"> *</span> </label>

                                            <input type="hidden" value="<?php echo $company_logo ?>" class="form-control"  name="old_company_logo">
                                            <input type="file" class="form-control" id="company_logo" name="company_logo">

                                            @if(isset($id) && $company_logo != "")
                                                    <img class="mt-2" src="<?= $company_logo ?>" alt="image description" width="100" height="100">
                                                @endif

                                            @if($company_logo =="" )
                                             @error('company_logo') <span class="text-danger">{{$message}}</span> @enderror
                                            @endif
                                          </div>

                                    </div>
                                </div>



                                 <div class="card-body border shadow-lg rounded p-4 m-2">

                                    <div class="section-title bg-light mb-4">
                                        <h6 class="fw-bold text-success mb-0">Bank Details</h6>
                                    </div>
                                    <div class="row g-3">




                                        <div class="col-xl-4">
                                            <label for="bank_name" class="form-label">Bank Name <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo $bank_name ?>" class="form-control" id="bank_name" name="bank_name" placeholder="Enter Bank Name">
                                            @error('bank_name') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="phone" class="form-label">Account No   <span class="text-danger"> *</span></label>
                                            <input type="number" value="<?php echo $account_no ?>" class="form-control" id="account_no" name="account_no"  placeholder="Enter Account Number">
                                        </div>

                                          <div class="col-xl-4">
                                            <label for="ifsc" class="form-label">IFSC  <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo $ifsc ?>" class="form-control" id="ifsc" name="ifsc" placeholder="Enter IFSC ">
                                            @error('ifsc') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="branch_name" class="form-label">Branch Name <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo $branch_name ?>" class="form-control" id="branch_name" name="branch_name" placeholder="Enter Branch Name">
                                            @error('branch_name') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>










                                        <div class="col-xl-4">
                                            <label for="company_logo" class="form-label">QR Code  <span class="text-danger"> *</span> </label>

                                            <input type="hidden" value="<?php echo $old_qr_code ?>" class="form-control"  name="old_qr_code">
                                            <input type="file" class="form-control" id="qr_code" name="qr_code">

                                            @if(isset($id) && $qr_code != "")
                                                    <img class="mt-2" src="<?= $qr_code ?>" alt="image description" width="100" height="100">
                                                @endif

                                            @if($qr_code =="" )
                                             @error('qr_code') <span class="text-danger">{{$message}}</span> @enderror
                                            @endif
                                        </div>


                                        <div class="col-xl-4">
                                            <label for="upi_id" class="form-label">UPI ID <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo $upi_id ?>" class="form-control" id="upi_id" name="upi_id" placeholder="Enter UPI ID">
                                            @error('upi_id') <span class="text-danger">{{$message}}</span> @enderror
                                        </div>

                                    </div>
                                </div>

                             </div>
                         </div>
                     </div>
                     <div class="d-flex justify-content-end gap-3 my-5">
                         <a href="" class="btn btn-light-light text-muted">Cancel</a>
                         <button type="submit" class="btn btn-primary">Save</button>
                     </div>
                 </form>
             </div>

         </div>



     </div>
 </main>



 <script>

$(document).ready(function(){

    // If already checked on page load
    if($('#free_delivery_checkbox').is(':checked')){
        $('#free_delivery_reason_div').show();
        $('#free_delivery_reason').prop('required', true);
    }

    $('#free_delivery_checkbox').change(function(){

        if($(this).is(':checked')){
            $('#free_delivery_reason_div').slideDown();
            $('#free_delivery_reason').prop('required', true);
        } else {
            $('#free_delivery_reason_div').slideUp();
            $('#free_delivery_reason').prop('required', false);
            $('#free_delivery_reason').val('');
        }

    });

});


     $(function() {
         $("#companyForm").validate({
             rules: {
                 company_name: {
                     required: true
                 },
                 phone: {
                     required: true
                 },
                 email: {
                     required: true
                 },
                 delivery_charge: {
                     required: true
                 },
                 company_address: {
                     required: true
                 },
                 state: {
                     required: true
                 },
                 pincode: {
                     required: true
                 },
                   fssai_no: {
                     required: true
                 },
                   invoice_no: {
                     required: true
                 },
                   direct_invoice_no: {
                     required: true
                 },
                   bank_name: {
                     required: true
                 },
                   branch_name: {
                     required: true
                 },
                   ifsc: {
                     required: true
                 },

                  account_no: {
                     required: true
                 },

                  upi_id: {
                     required: true
                 },
                 message: {
                     required: true
                 },











             },
             messages: {
                 company_name: {
                     required: "Please enter company name"
                 },
                 phone: {
                     required: "Please enter phone no"
                 },
                 email: {
                     required: "Please enter email"
                 },
                 delivery_charge: {
                     required: "Please select delivery charge"
                 },
                 company_address: {
                     required: "Please enter address"
                 },
                 state: {
                     required: "Please enter state"
                 },
                 pincode: {
                     required: "Please enter pincode "
                 },

                fssai_no: {
                     required: "Please enter FSSAI No "
                 },

                invoice_no: {
                     required: "Please enter invoice No "
                 },

                direct_invoice_no: {
                     required: "Please enter direct invoice No "
                 },

                bank_name: {
                     required: "Please enter bank name "
                 },
                branch_name: {
                     required: "Please enter branch name "
                 },

                ifsc: {
                     required: "Please enter ifsc name "
                 },
                account_no: {
                     required: "Please enter account no "
                 },

                upi_id: {
                     required: "Please enter UPI ID "
                 },
                 message: {
                     required: "Please enter message "
                 },

             },
             errorElement: "span",
             errorPlacement: function(error, element) {
                 error.addClass("text-danger small");
                if (element.hasClass("select2-hidden-accessible")) {
                    error.insertAfter(element.next('.select2'));
                } else {
                    error.insertAfter(element);
                }
             }
         });
     });

 </script>
 @endsection
