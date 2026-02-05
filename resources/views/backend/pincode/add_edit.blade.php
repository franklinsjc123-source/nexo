 @extends('backend.app_template')
 @section('title','Pincode Store or Update')
 @section('content')
 <?php
     $id                  = isset($record->id) ? $record->id : '';
    $pincode           = isset($record->pincode) ? $record->pincode:'';
    $status           = isset($record->status) ? $record->status:'';

    $type              = ($id == '')   ? 'Create' : 'Update';

    ?>
 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Pincode</a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="pincodeForm" action="<?= route('storeUpdatePincode') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> Pincode</h5>
                                 <div class="float-end">
                                     <a href="<?= route('pincode') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>
                             <input type="hidden" name="id" value="<?= $id ?>" />
                             <div class="card-body">
                                 <div class="row g-4">
                                    <div class="col-xl-4">
                                         <label for="pincode" class="form-label">Pincode <span class="text-danger"> *</span></label>
                                         <input type="text" maxlength="6" minlength="6"  value="<?php echo $pincode?>" class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode" onkeyup="commonCheckExist(this,'pincode', 'pincode', this.value)">
                                        <span class="text-danger error-message"></span>
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
         <!-- Submit Section -->
     </div>
 </main>
 <script>
     $(function() {
         $("#pincodeForm").validate({
             rules: {

                 pincode: {
                     required: true
                 },


             },
             messages: {

                 pincode: {
                     required: "Please Enter Pincode"
                 },

             },
             errorElement: "span",
             errorPlacement: function(error, element) {
                 error.addClass("text-danger");
                 error.insertAfter(element);
             }
         });
     });
 </script>
 @endsection
