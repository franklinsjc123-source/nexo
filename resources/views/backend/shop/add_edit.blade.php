 @extends('backend.app_template')
 @section('title','Shop Store or Update')
 @section('content')
 <?php


    $id                     = isset($records->id) ? $records->id : '';
    $user_id                = isset($records->user_id) ? $records->user_id : '';
    $email                  = isset($records->userData->email) ? $records->userData->email : '';
    $category               = isset($records->category) ? $records->category : '';
    $shop_name              = isset($records->shop_name) ? $records->shop_name : '';
    $gst_no                 = isset($records->gst_no) ? $records->gst_no : '';
    $contact_no             = isset($records->contact_no) ? $records->contact_no : '';
    $start_time             = isset($records->start_time) ? $records->start_time : '';
    $end_time               = isset($records->end_time) ? $records->end_time : '';
    $address                = isset($records->address) ? $records->address : '';
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
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Shop</a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>
         
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="shopForm" action="<?= route('storeUpdateShop') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> Shop </h5>
                                 <div class="float-end">
                                     <a href="<?= route('shop') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>
                             <input type="hidden" name="id" value="<?= $id ?>" />
                             <input type="hidden" name="user_id" value="<?= $user_id ?>" />
                             <div class="card-body">
                                 <div class="row g-4">

                                    <div class="col-xl-4">
                                        <label class="form-label">Shop Category <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="category" name="category">
                                            <option value="">--select--</option>
                                                <?php
                                                    if (isset($categoryData)) {
                                                        foreach ($categoryData as $val) { ?>
                                                        <option <?=(old('category', $category) == $val->id)? 'selected':'' ?> value="<?php echo $val->id ?>"><?php echo ucwords($val->category_name) ?></option>
                                                <?php }
                                                }
                                                ?>
                                        </select>

                                        @error('category') <span class="text-danger">{{$message}}</span> @enderror

                                    </div>

                                      <div class="col-xl-4">
                                            <label for="shop_name" class="form-label">Shop Name <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo old('shop_name',$shop_name)  ?>" class="form-control" id="shop_name" name="shop_name" placeholder="Enter Shop Name">
                                            @error('shop_name') <span class="text-danger">{{$message}}</span> @enderror

                                        </div>

                                        <div class="col-xl-4">
                                            <label for="contact_no" class="form-label">
                                                Contact Number <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="Enter Contact Number" value="<?= old('contact_no',$contact_no) ?? '' ?>" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g,'');">
                                            @error('contact_no') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="email" class="form-label">Email <span class="text-danger"> *</span></label>
                                            <input type="text" value="<?php echo old('email',$email) ?>" class="form-control" id="email" name="email" placeholder="Enter Email" onkeyup="commonCheckExist(this,'users', 'email', this.value)">
                                            <span class="text-danger error-message"></span>
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="start_time" class="form-label">
                                                Shop Start Time <span class="text-danger">*</span>
                                            </label>
                                            <input type="time" class="form-control" id="start_time" name="start_time" value="<?= old('start_time',$start_time) ?? '' ?>" >
                                            @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="end_time" class="form-label">
                                                Shop End Time <span class="text-danger">*</span>
                                            </label>
                                            <input type="time" class="form-control" id="end_time" name="end_time" value="<?= old('end_time',$end_time) ?? '' ?>"
                                            >
                                            @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                          <div class="col-xl-4">
                                            <label for="gst_no" class="form-label">
                                                GST Number <span class="text-danger"></span>
                                            </label>
                                            <input type="text" class="form-control" id="gst_no" name="gst_no" placeholder="Enter GST Number" value="<?= old('gst_no',$gst_no) ?? '' ?>">
                                            @error('gst_no') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="address" class="form-label">
                                                Shop Address <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter Shop Address"
                                            ><?= old('address',$address) ?? '' ?></textarea>
                                            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="col-xl-4">

                                        <label for="photo_path" class="form-label">Shop Image<span class="text-danger"> *</span>  </label>

                                        <input type="hidden" value="<?php echo $photo_path ?>" class="form-control"  name="old_photo_path">
                                        <input type="file" class="form-control" id="photo_path" name="photo_path">
                                          <input type="hidden" id="has_old_photo_path" value="<?= !empty($photo_path) ? 1 : 0 ?>">

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
         $("#shopForm").validate({
             rules: {

                category: {
                    required: true
                },
                shop_name: {
                    required: true
                },

                contact_no: {
                    required: true
                },
                email: {
                    required: true
                },
                start_time: {
                    required: true
                },
                end_time: {
                    required: true
                },
                 address: {
                    required: true
                },

               photo_path: {
                    required: function () {
                        return $('#has_old_photo_path').val() == 0;
                    }
                }
             },
             messages: {

                category: {
                    required: "Please enter category name"
                },
                contact_no: {
                    required: "Please enter contact no"
                },
                email: {
                    required: "Please enter email id"
                },
                shop_name: {
                    required: "Please enter shop name"
                },

                start_time: {
                    required: "Please enter start time"
                },

                end_time: {
                    required: "Please enter end time"
                },

                address: {
                    required: "Please enter address"
                },

                  slider_photo_path: {
                    required: "Please upload Shop image"
                }

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
