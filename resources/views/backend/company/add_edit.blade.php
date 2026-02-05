 @extends('backend.app_template')
 @section('title','Company Store or Update')
 @section('content')
 <?php


    $id                     = isset($record->id) ? $record->id : '';
    $company_name              = isset($record->company_name) ? $record->company_name : '';
    $phone                  = isset($record->phone) ? $record->phone : '';
    $email                  = isset($record->email) ? $record->email : '';
    $delivery_charge        = isset($record->delivery_charge) ? $record->delivery_charge : '';
    $company_address        = isset($record->company_address) ? $record->company_address : '';
    $state                  = isset($record->state) ? $record->state : '';
    $pincode                = isset($record->pincode) ? $record->pincode : '';
    $pan_no                 = isset($record->pan_no) ? $record->pan_no : '';
    $gst_no                 = isset($record->gst_no) ? $record->gst_no : '';
    $company_logo           = isset($record->logo) ? $record->logo : '';
    $old_company_logo       = isset($record->logo) ? $record->logo : '';
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
                                            <label for="pan_no" class="form-label">Pan No </label>
                                            <input type="text" value="<?php echo $pan_no ?>" class="form-control" id="pan_no" name="pan_no"  placeholder="Enter Pan No">
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="gst_no" class="form-label">GST No </label>
                                            <input type="text" value="<?php echo $gst_no ?>" class="form-control" id="gst_no" name="gst_no" placeholder="Enter GST No" >
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
                                            <label for="pincode" class="form-label">Pincode  <span class="text-danger"> *</span> </label>
                                            <input type="text" value="<?php echo $pincode ?>" class="form-control" id="pincode" name="pincode" maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g,'');" placeholder="Enter Pincode">
                                        </div>


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

     $('#saveMakeBtn').click(function() {
         let name = $('#zone_name').val();

         $.ajax({
             url: "{{ route('store') }}",

             type: "POST",
             data: {
                 name: name,
                 _token: "{{ csrf_token() }}"
             },

             success: function(res) {
                 console.log(res);
                 $('#makeModal').modal('hide');

                 $('#zone_name').val('');

                 $('#zonal').append(
                     `<option value="${res.id}" selected >${res.zone_name}</option>`
                 );
             },
             error: function() {
                 $('#make_error').text("Name field is required");
             }
         });
     });


     // master Bill add
     $('#saveBillBtn1').click(function() {
         let name = $('#bill_type_name').val();

         $.ajax({
             url: "{{ route('billstore') }}",
             type: "POST",
             data: {
                 name: name,
                 _token: "{{ csrf_token() }}"
             },
             success: function(res) {
                 console.log(res);
                 $('#makeModal1').modal('hide');
                 $('#bill_type_name').val('');
                 $('#bill_type').append(
                     `<option value="${res.id}" selected>${res.bill_type_name}</option>`
                 );
             },
             error: function() {
                 $('#make_error').text("Name field is required");
             }
         });
     });
 </script>
 @endsection
