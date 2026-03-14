 @extends('backend.app_template')
 @section('title','Tax  Store or Update')
 @section('content')
 <?php
    $id               = isset($record->id) ? $record->id : '';
    $tax_percentage   = isset($record->tax_percentage) ? $record->tax_percentage:'';
    $status           = isset($record->status) ? $record->status:'';
    $type             = ($id == '')   ? 'Create' : 'Update';

    ?>
 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Tax</a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="taxForm" action="<?= route('storeUpdateTax') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> Tax</h5>
                                 <div class="float-end">
                                     <a href="<?= route('tax') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>
                             <input type="hidden" name="id" value="<?= $id ?>" />
                             <div class="card-body">
                                 <div class="row g-4">
                                    <div class="col-xl-4">
                                         <label for="tax_percentage" class="form-label">Tax Percentage <span class="text-danger"> *</span></label>
                                         <input type="text"  value="<?php echo $tax_percentage?>" class="form-control" id="tax_percentage" name="tax_percentage" placeholder="Enter tax percentage" onkeyup="commonCheckExist(this,'tax','tax_percentage', this.value, {{ $id ?? 'null' }})"  >
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
         $("#taxForm").validate({
             rules: {

                 tax_percentage: {
                     required: true
                 },


             },
             messages: {

                 tax_percentage: {
                     required: "Please enter tax percentage"
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
