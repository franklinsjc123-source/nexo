 @extends('backend.app_template')
 @section('title','Permission Store or Update')
 @section('content')
 <?php
    $id                  = isset($record->id) ? $record->id : '';
    $category           = isset($record->category) ? $record->category : '';
    $name           = isset($record->name) ? $record->name : '';
    $status           = isset($record->status) ? $record->status : '';

    $type              = ($id == '')   ? 'Create' : 'Update';

    ?>
 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Permission</a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="zonetForm" action="<?= route('storeUpdatePermission') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                         <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> Permission</h5>
                                 <div class="float-end">
                                     <a href="<?= route('permission') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>
                             <input type="hidden" name="id" value="<?= $id ?>" />
                             <div class="card-body">
                                 <div class="row g-4">


                                     <div class="col-xl-4">
                                         <label class="form-label">Menu<span class="text-danger">*</span></label>
                                         <select class="form-control select2" id="category" name="category">
                                             <option value="">--select--</option>
                                             <?php
                                                if (isset($categoryData)) {
                                                    foreach ($categoryData as $val) { ?>
                                                     <option <?= ($category == $val->id) ? 'selected':'' ?> value="<?php echo $val->id ?>"><?php echo ucwords($val->name) ?></option>
                                             <?php }
                                                }
                                                ?>
                                         </select>
                                     </div>

                                     <div class="col-xl-4">
                                        <label class="form-label">Permission Name <span class="text-danger">*</span></label>
                                        <input name="permission_name" id="permission_name" placeholder="Permission Name" value="<?= $name ?>" type="text" class="form-control">
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
         $("#zonetForm").validate({
             rules: {

                 category: {
                     required: true
                 },
                 permission_name: {
                     required: true
                 },

             },
             messages: {

                 category: {
                     required: "Please Select category"
                 },
                 permission_name: {
                     required: "Please Enter Permission Name"
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