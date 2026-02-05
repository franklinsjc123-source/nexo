 @extends('backend.app_template')
 @section('title','Slider Store or Update')
 @section('content')
 <?php


    $id                     = isset($records->id) ? $records->id : '';
    $slider_photo_path      = isset($records->file_path) ? $records->file_path:'';
    $status                 = isset($records->status) ? $records->status:'';
    $type                   = ($id == '')   ? 'Create' : 'Update';

    ?>
 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Slider</a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="sliderForm" action="<?= route('storeUpdateSlider') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> Slider</h5>
                                 <div class="float-end">
                                     <a href="<?= route('slider') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>
                             <input type="hidden" name="id" value="<?= $id ?>" />
                             <div class="card-body">
                                 <div class="row g-4">

                                          <div class="col-xl-4">
                                            <label for="slider_photo_path" class="form-label">Slider <span class="text-danger"> *</span></label>

                                            <input type="hidden" value="<?php echo $slider_photo_path ?>" class="form-control"  name="old_slider_photo_path">
                                            <input type="file" class="form-control" id="slider_photo_path" name="slider_photo_path">

                                            @if(isset($id) && $slider_photo_path != "")
                                                    <img class="mt-2" src="<?= $slider_photo_path ?>" alt="image description" width="200" height="100">
                                                @endif

                                            @if($slider_photo_path =="" )
                                            @error('slider_photo_path') <span class="text-danger">{{$message}}</span> @enderror
                                            @endif
                                          </div>

                                          <input type="hidden" id="has_old_slider" value="<?= !empty($slider_photo_path) ? 1 : 0 ?>">

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
