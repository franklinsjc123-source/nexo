 @extends('backend.app_template')
 @section('title','Slider Store or Update')
 @section('content')
 <?php


    $id                     = isset($records->id) ? $records->id : '';
    $category_name          = isset($records->category_name) ? $records->category_name : '';
    $photo_path             = isset($records->file_path) ? $records->file_path:'';
    $status                 = isset($records->status) ? $records->status:'';
    $type                   = ($id == '')   ? 'Create' : 'Update';

    ?>
 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Category</a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="categoryForm" action="<?= route('storeUpdateCategory') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> Category</h5>
                                 <div class="float-end">
                                     <a href="<?= route('category') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>
                             <input type="hidden" name="id" value="<?= $id ?>" />
                             <div class="card-body">
                                 <div class="row g-4">

                                        <div class="col-xl-4">
                                            <label for="category_name" class="form-label">Category Name <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo $category_name ?>" class="form-control" id="category_name" name="category_name" placeholder="Enter Category Name">
                                            @error('category_name') <span class="text-danger">{{$message}}</span> @enderror

                                        </div>


                                          <div class="col-xl-4">
                                            <label for="photo_path" class="form-label">Category Image<span class="text-danger"> *</span> (Upload 512x512 size) </label>

                                            <input type="hidden" value="<?php echo $photo_path ?>" class="form-control"  name="old_photo_path">
                                            <input type="file" class="form-control" id="photo_path" name="photo_path">

                                            @if(isset($id) && $photo_path != "")
                                                    <img class="mt-2" src="<?= $photo_path ?>" alt="image description" width="200" height="100">
                                                @endif

                                            @if($photo_path =="" )
                                            @error('photo_path') <span class="text-danger">{{$message}}</span> @enderror
                                            @endif
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
         $("#categoryForm").validate({
             rules: {

                 category_name: {
                     required: true
                 },


             },
             messages: {

                 category_name: {
                     required: "Please enter category name"
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
