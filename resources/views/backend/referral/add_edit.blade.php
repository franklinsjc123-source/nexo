 @extends('backend.app_template')
 @section('title','Referral Store or Update')
 @section('content')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

 <?php
     $id             = isset($record->id) ? $record->id : '';
    $name            = isset($record->name) ? $record->name:'';
    $mobile          = isset($record->mobile) ? $record->mobile:'';
    $referral_code   = isset($record->referral_code) ? $record->referral_code: $new_referral_code;
    $status          = isset($record->status) ? $record->status:'';
    $type            = ($id == '')   ? 'Create' : 'Update';

    ?>
 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Referral</a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="referralForm" action="<?= route('storeUpdateReferral') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> Referral</h5>
                                 <div class="float-end">
                                     <a href="<?= route('referral') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>
                             <input type="hidden" name="id" value="<?= $id ?>" />
                               <div class="card-body">

                                <div class="card-body border shadow-lg rounded p-4 m-2">

                                    <div class="section-title bg-light mb-4">
                                        <h6 class="fw-bold text-success mb-0">Referral Details</h6>
                                    </div>
                                    <div class="row g-4">
                                        <div class="col-xl-4">
                                            <label for="name" class="form-label">Name <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo old('name',$name) ?>" class="form-control" id="name" name="name" placeholder="Enter Name" >
                                            <span class="text-danger error-message"></span>
                                        </div>


                                        <div class="col-xl-4">
                                            <label for="email" class="form-label">Referral Code <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo old('referral_code',$referral_code) ?>" class="form-control" id="referral_code" name="referral_code" placeholder="Enter Referral Code" readonly>
                                            <span class="text-danger error-message"></span>
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="mobile" class="form-label">Mobile <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo  old('mobile',$mobile)?>" maxlength="10" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile" onkeyup="commonCheckExist(this,'referral','mobile', this.value, {{ $id ?? 'null' }})">
                                            <span class="text-danger error-message"></span>
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
         $("#referralForm").validate({
             rules: {


                 mobile: {
                     required: true
                 },
                  name: {
                     required: true
                 },

             },
             messages: {
                 name: {
                     required: "Please enter name"
                 },


                 mobile: {
                     required: "Please enter mobile no"
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
