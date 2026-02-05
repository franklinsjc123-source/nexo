 @extends('backend.app_template')
 @section('title','User Store or Update')
 @section('content')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

 <?php
     $id             = isset($record->id) ? $record->id : '';
    $name          = isset($record->name) ? $record->name:'';
    $mobile          = isset($record->mobile) ? $record->mobile:'';
    $email           = isset($record->email) ? $record->email:'';
    $status          = isset($record->status) ? $record->status:'';
    $type            = ($id == '')   ? 'Create' : 'Update';

    ?>
 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">User</a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="zonetForm" action="<?= route('storeUpdateUser') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> User</h5>
                                 <div class="float-end">
                                     <a href="<?= route('users') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>
                             <input type="hidden" name="id" value="<?= $id ?>" />
                               <div class="card-body">

                                <div class="card-body border shadow-lg rounded p-4 m-2">

                                    <div class="section-title bg-light mb-4">
                                        <h6 class="fw-bold text-success mb-0">User Details</h6>
                                    </div>
                                    <div class="row g-4">

                                         <div class="col-xl-4">
                                         <label for="name" class="form-label">Name <span class="text-danger"> *</span></label>
                                         <input type="text" value="<?php echo $name?>" class="form-control" id="name" name="name" placeholder="Enter Name" >
                                        <span class="text-danger error-message"></span>
                                    </div>


                                    <div class="col-xl-4">
                                         <label for="email" class="form-label">Email <span class="text-danger"> *</span></label>
                                         <input type="text" value="<?php echo $email?>" class="form-control" id="email" name="email" placeholder="Enter Email" onkeyup="commonCheckExist(this,'users', 'email', this.value)">
                                        <span class="text-danger error-message"></span>
                                    </div>

                                       <div class="col-xl-4">
                                         <label for="mobile" class="form-label">Mobile <span class="text-danger"> *</span></label>
                                         <input type="text" value="<?php echo $mobile?>" maxlength="10" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile" onkeyup="commonCheckExist(this,'users', 'mobile', this.value)">
                                        <span class="text-danger error-message"></span>
                                    </div>



                                    <div class="col-xl-4">
                                        <label for="password" class="form-label">
                                            Password <span class="text-danger">*</span>
                                        </label>

                                        <div class="position-relative">
                                            <input type="password"
                                                class="form-control pe-5"
                                                id="password"
                                                name="password"
                                                placeholder="Enter Password">

                                            <span class="position-absolute top-50 end-0 translate-middle-y me-3"
                                                style="cursor: pointer;"
                                                onclick="togglePassword()">
                                                <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                            </span>
                                        </div>

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

    function togglePassword() {
    const password = document.getElementById('password');
    const icon = document.getElementById('togglePasswordIcon');

    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}


     $(function() {
         $("#zonetForm").validate({
             rules: {

                 email: {
                     required: true
                 },
                 mobile: {
                     required: true
                 },

             },
             messages: {

                 email: {
                     required: "Please Enter Email"
                 },
                 mobile: {
                     required: "Please Enter Mobile No"
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
