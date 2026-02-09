 @extends('backend.app_template')
 @section('title','Product Upload ')
 @section('content')

 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Product Upload </a></li>
                         <li class="breadcrumb-item active" aria-current="page"></li>
                     </ol>
                 </nav>
             </div>
         </div>
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="categoryForm" action="<?= route('storeProductUpload') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"> Product Upload</h5>
                                 <div class="float-end">
                                     <a href="<?= route('product-upload') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>

                             <div class="card-body">
                                 <div class="row g-4">




                                          <div class="col-xl-4">
                                            <label for="file" class="form-label">Upload CSV<span class="text-danger"> *</span>
                                            <input type="file" class="form-control" id="file" name="file"   accept=".csv" >


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
                photo_path: {
                    required: function () {
                        return $('#has_old_photo_path').val() == 0;
                    }
                }
            },
             messages: {

                 category_name: {
                     required: "Please enter category name"
                 },
                  photo_path: {
                    required: "Please upload Category image"
                }

             },
             errorElement: "span",
             errorPlacement: function(error, element) {
                 error.addClass("text-danger");
                 error.insertAfter(element);
             }
         });
     });

       $(function() {
         $("#sliderForm").validate({
           rules: {
                slider_photo_path: {
                    required: function () {
                        return $('#has_old_slider').val() == 0;
                    }
                }
            },
            messages: {
                slider_photo_path: {
                    required: "Please upload slider image"
                }
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
