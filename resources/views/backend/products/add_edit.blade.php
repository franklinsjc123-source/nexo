 @extends('backend.app_template')
 @section('title','Shop Store or Update')
 @section('content')
 <?php


    $id                     = isset($records->id) ? $records->id : '';
    $category               = isset($records->category) ? $records->category : '';
    $shop                   = isset($records->shop) ? $records->shop : '';
    $product_name                 = isset($records->product_name) ? $records->product_name : '';
    $original_price             = isset($records->original_price) ? $records->original_price : '';
    $discount_price             = isset($records->discount_price) ? $records->discount_price : '';
    $end_time               = isset($records->end_time) ? $records->end_time : '';
    $product_description                = isset($records->product_description) ? $records->product_description : '';
    $product_image             = isset($records->product_image) ? $records->product_image:'';
    $status                 = isset($records->status) ? $records->status:'';
    $type                   = ($id == '')   ? 'Create' : 'Update';

    ?>
 <main class="app-wrapper">
     <div class="container-fluid">

         <div class="d-flex align-items-center mt-2 mb-2">

             <div class="flex-shrink-0">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb justify-content-end mb-0">
                         <li class="breadcrumb-item"><a href="javascript:void(0)">Product</a></li>
                         <li class="breadcrumb-item active" aria-current="page"><?= $type ?></li>
                     </ol>
                 </nav>
             </div>
         </div>
         <div class="row">
             <div class="col-xl-12 col-xxl-12">
                 <form method="POST" id="productForm" action="<?= route('storeUpdateProduct') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> Product </h5>
                                 <div class="float-end">
                                     <a href="<?= route('product') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>
                             <input type="hidden" name="id" value="<?= $id ?>" />
                             <div class="card-body">
                                 <div class="row g-4">

                                    <div class="col-xl-4">
                                        <label class="form-label"> Category <span class="text-danger">*</span></label>
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
                                        <label class="form-label"> Shop <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="shop" name="shop">
                                            <option value="">--select--</option>
                                                <?php
                                                    if (isset($shopData)) {
                                                        foreach ($shopData as $val) { ?>
                                                        <option <?=(old('shop', $shop) == $val->id)? 'selected':'' ?> value="<?php echo $val->id ?>"><?php echo ucwords($val->shop_name) ?></option>
                                                <?php }
                                                }
                                                ?>
                                        </select>

                                        @error('shop') <span class="text-danger">{{$message}}</span> @enderror

                                    </div>

                                        <div class="col-xl-4">
                                            <label for="contact_no" class="form-label">
                                                Product Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter Product Name" value="<?= old('product_name',$product_name) ?? '' ?>"  >
                                            @error('product_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>



                                          <div class="col-xl-4">
                                            <label for="original_price" class="form-label">
                                                Original Price <span class="text-danger"></span>
                                            </label>
                                            <input type="text" class="form-control" id="original_price" name="original_price" placeholder="Enter Original Price" value="<?= old('original_price',$original_price) ?? '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g,'');">
                                            @error('original_price') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="original_price" class="form-label">
                                                Discount Price <span class="text-danger"></span>
                                            </label>
                                            <input type="text" class="form-control" id="discount_price" name="discount_price" placeholder="Enter Discount Price" value="<?= old('discount_price',$discount_price) ?? '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g,'');">
                                            @error('discount_price') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>


                                        <div class="col-xl-4">
                                            <label for="product_description" class="form-label">
                                                Product Description <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control" id="product_description" name="product_description"  placeholder="Enter Product Description"
                                            ><?= old('product_description',$product_description) ?? '' ?></textarea>
                                            @error('product_description') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>



                                        <div class="col-xl-4">

                                        <label for="product_image" class="form-label">product Image<span class="text-danger"> *</span>  </label>

                                        <input type="hidden" value="<?php echo $product_image ?>" class="form-control"  name="old_product_image">
                                        <input type="file" class="form-control" id="product_image" name="product_image">

                                        @if(isset($id) && $product_image != "")
                                                <img class="mt-2" src="<?= $product_image ?>" alt="image description" width="200" height="100">
                                            @endif

                                        @if($product_image =="" )
                                            @error('product_image') <span class="text-danger">{{$message}}</span> @enderror
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
                    required: true
                },
             },
             messages: {

                category: {
                    required: "Please enter category name"
                },
                 contact_no: {
                    required: "Please enter contact no"
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
