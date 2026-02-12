 @extends('backend.app_template')
 @section('title','Offers Store or Update')
 @section('content')
 <?php


    $id                     = isset($records->id) ? $records->id : '';
    $shop_id                = isset($records->shop_id) ? $records->shop_id : '';
    $offer_code             = isset($records->offer_code) ? $records->offer_code : '';
    $expiry_date            = isset($records->expiry_date) ? $records->expiry_date:'';
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
                 <form method="POST" id="shopForm" action="<?= route('storeUpdateOffer') ?>" enctype="multipart/form-data">
                     @csrf
                     <div>
                        <div class="card">
                             <span></span>
                             <!-- Logistics Details Section -->
                             <div class="card-header">
                                 <h5 class="mb-0"><?= $type ?> Shop </h5>
                                 <div class="float-end">
                                     <a href="<?= route('offers') ?>" class="btn btn-primary">Back</a>
                                 </div>
                             </div>
                             <input type="hidden" name="id" value="<?= $id ?>" />
                             <div class="card-body">
                                 <div class="row g-4">

                                    <div class="col-xl-4">
                                        <label class="form-label">Shop  <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="shop_id" name="shop_id">
                                            <option value="">--select--</option>
                                                <?php
                                                    if (isset($shopData)) {
                                                        foreach ($shopData as $val) { ?>
                                                        <option <?=(old('shop_id', $shop_id) == $val->id)? 'selected':'' ?> value="<?php echo $val->id ?>"><?php echo ucwords($val->shop_name) ?></option>
                                                <?php }
                                                }
                                                ?>
                                        </select>

                                        @error('category') <span class="text-danger">{{$message}}</span> @enderror

                                    </div>



                                        <div class="col-xl-4">
                                            <label for="offer_code" class="form-label">
                                                Offer Code <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="offer_code" name="offer_code" placeholder="Enter Offer Code" value="<?= old('offer_code',$offer_code) ?? '' ?>"  onkeyup="commonCheckExist(this,'offers', 'offer_code', this.value)">
                                            @error('offer_code') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>


                                         <div class="col-xl-4">
                                            <label for="expiry_date" class="form-label">
                                                Expiry Date <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="expiry_date" min="<?= date('Y-m-d') ?>"  name="expiry_date" placeholder="Enter Offer Code" value="<?= old('expiry_date',$expiry_date) ?? '' ?>" >
                                            @error('expiry_date') <span class="text-danger">{{ $message }}</span> @enderror
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

                shop_id: {
                    required: true
                },
                offer_code: {
                    required: true
                },

                offer_code: {
                    required: true
                },


             },
             messages: {

                shop_id: {
                    required: "Please select shop name"
                },
                 offer_code: {
                    required: "Please enter offer code"
                },

                offer_code: {
                    required: "Please enter offer code"
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
