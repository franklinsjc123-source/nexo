 @extends('backend.app_template')
 @section('title','Pincode Store or Update')
 @section('content')
 <?php
     $id                  = isset($record->id) ? $record->id : '';
    $unit_name           = isset($record->unit_name) ? $record->unit_name:'';
    $status           = isset($record->status) ? $record->status:'';

    $type              = ($id == '')   ? 'Create' : 'Update';

    ?>
 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Unit</a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="unitForm" action="<?= route('storeUpdateUnit') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> Unit</h5>
                                 <div class="float-end">
                                     <a href="<?= route('unit') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>
                             <input type="hidden" name="id" value="<?= $id ?>" />
                             <div class="card-body">
                                 <div class="row g-4">
                                    <div class="col-xl-4">
                                         <label for="unit_name" class="form-label">Unit <span class="text-danger"> *</span></label>
                                         <input type="text"  value="<?php echo $unit_name?>" class="form-control" id="unit_name" name="unit_name" placeholder="Enter Unit Name" onkeyup="commonCheckExist(this,'unit','unit_name', this.value, {{ $id ?? 'null' }})"  >
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
         $("#unitForm").validate({
             rules: {

                 unit_name: {
                     required: true
                 },


             },
             messages: {

                 unit_name: {
                     required: "Please enter unit name"
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
