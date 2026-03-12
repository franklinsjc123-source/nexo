 @extends('backend.app_template')
 @section('title','Shop Store or Update')
 @section('content')
 <?php


    $id                     = isset($records->id) ? $records->id : '';
    $category               = isset($records->category) ? $records->category : '';
    $shop                   = isset($records->shop) ? $records->shop : '';
    $qty                    = isset($records->qty) ? $records->qty : '';
    $unit                   = isset($records->unit) ? $records->unit : '';
    $product_name           = isset($records->product_name) ? $records->product_name : '';
    $food_type              = isset($records->food_type) ? $records->food_type : '';
    $hsn_code               = isset($records->hsn_code) ? $records->hsn_code : '';
    $original_price         = isset($records->original_price) ? $records->original_price : '';
    $discount_price         = isset($records->discount_price) ? $records->discount_price : '';
    $end_time               = isset($records->end_time) ? $records->end_time : '';
    $product_description    = isset($records->product_description) ? $records->product_description : '';
    $product_image          = isset($records->product_image) ? $records->product_image:'';
    $status                 = isset($records->status) ? $records->status:'';
    $type                   = ($id == '')   ? 'Create' : 'Update';

    ?>

    <style>
      .input-error{
    border:1px solid red !important;
}

.select2-selection.input-error{
    border:1px solid red !important;
}
    </style>
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

                                    <?php

                                      if(Auth::user()->auth_level == 4)  {

                                           $shop_id = \App\Models\Shop::where('user_id', auth()->id())->value('id');
                                           $is_hotel = \App\Models\Shop::where('id',  $shop_id)->value('is_hotel');
                                        ?>
                                        <input type="hidden" name="shop"  value="{{  $shop_id }}">

                                    <?php  } else { ?>
                                        <div class="col-xl-4">
                                            <label class="form-label"> Shop <span class="text-danger">*</span></label>
                                            <select class="form-control select2" id="shop" name="shop">
                                                <option value="">--select--</option>

                                            </select>

                                            @error('shop') <span class="text-danger">{{$message}}</span> @enderror

                                        </div>


                                    <?php }  ?>



                                        <div class="col-xl-4">
                                            <label for="contact_no" class="form-label">
                                                Product Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter Product Name" value="<?= old('product_name',$product_name) ?? '' ?>"  >
                                            @error('product_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <?php  if (Auth::user()->auth_level == 4 && $is_hotel == 1 )  {  ?>

                                            <div class="col-xl-4"  >
                                                <label for="food_type" class="form-label">
                                                    Food Type <span class="text-danger">*</span>
                                                </label>

                                                <select name="food_type" id="food_type" class="form-select ">
                                                    <option value="">Select Type</option>
                                                    <option value="veg" {{ old('food_type', $food_type) == 'veg' ? 'selected' : '' }}>Veg</option>
                                                    <option value="non_veg" {{ old('food_type', $food_type) == 'non_veg' ? 'selected' : '' }}>Non-Veg</option>
                                                </select>

                                                @error('food_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        <?php } else { ?>

                                            <div class="col-xl-4" id="food_type_div" style="display:none;">
                                                <label for="food_type" class="form-label">
                                                    Food Type <span class="text-danger">*</span>
                                                </label>

                                                <select name="food_type" id="food_type" class="form-select ">
                                                    <option value="">Select Type</option>
                                                    <option value="veg" {{ old('food_type', $food_type) == 'veg' ? 'selected' : '' }}>Veg</option>
                                                    <option value="non_veg" {{ old('food_type', $food_type) == 'non_veg' ? 'selected' : '' }}>Non-Veg</option>
                                                </select>

                                                @error('food_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                     <?php    } ?>



                                         <div class="col-xl-4">
                                            <label for="contact_no" class="form-label">
                                                HSN Code <span class="text-danger"></span>
                                            </label>
                                            <input type="text" class="form-control" id="hsn_code" name="hsn_code"  maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g,'');"  placeholder="Enter HSN Code" value="<?= old('hsn_code',$hsn_code) ?? '' ?>"  >
                                            @error('hsn_code') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>




                                        <div class="col-xl-4">
                                            <label for="product_description" class="form-label">
                                                Product Description <span class="text-danger">*</span>
                                            </label>
                                            <textarea   rows="1" class="form-control" lenght="1" id="product_description" name="product_description"  placeholder="Enter Product Description"
                                            ><?= old('product_description',$product_description) ?? '' ?></textarea>
                                            @error('product_description') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>



                                        <div class="col-xl-4">

                                            <label for="product_image" class="form-label">product Image<span class="text-danger"> *</span>  </label>

                                            <input type="hidden" value="<?php echo $product_image ?>" class="form-control"  name="old_product_image">
                                            <input type="file" class="form-control" id="product_image" name="product_image[]" multiple>

                                             @if(!empty($productImages) && $productImages->count() > 0)
                                                    <div class="mt-2 d-flex flex-wrap gap-2">

                                                        @foreach($productImages as $img)
                                                            <div style="position:relative;">
                                                                <img src="{{ $img->product_image }}"
                                                                    width="80"
                                                                    height="80"
                                                                    style="border-radius:6px; border:1px solid #ddd;">
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                @endif

                                            @if($product_image =="" )
                                                @error('product_image') <span class="text-danger">{{$message}}</span> @enderror
                                            @endif
                                        </div>


                                        <input type="hidden" id="has_old_product_image" value="<?= !empty($product_image) ? 1 : 0 ?>">

                                        <div id="quantityWrapper">




                                           @if($productAttributes->isNotEmpty())
                                                

                                                @foreach($productAttributes as $index => $ba)
                                                    <div class="row mt-5 quantity-row align-items-end" data-index="{{ $index }}">

                                                        <div class="col-xl-3">
                                                            <label class="form-label"> Unit <span class="text-danger"></span></label>
                                                            <select class="form-control select2"  name="unit[]">
                                                            <option value="">--select--</option>
                                                                    <?php
                                                                        if (isset($unitData)) {
                                                                            foreach ($unitData as $val) { ?>
                                                                                <option value="{{ $val->id }}"
                                                                                    {{ (old('unit.'.$index, $ba->unit) == $val->id) ? 'selected' : '' }}>
                                                                                    {{ ucwords($val->unit_name) }}
                                                                                </option>
                                                                            <?php }
                                                                        }
                                                                    ?>

                                                            </select>
                                                            @error('unit') <span class="text-danger">{{$message}}</span> @enderror
                                                        </div>


                                                        <div class="col-xl-3">
                                                            <label for="original_price" class="form-label">
                                                                Original Price <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="text" class="form-control"  name="original_price[]" placeholder="Enter Original Price" value="<?= old('original_price',$ba->original_price) ?? '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g,'');">
                                                            @error('original_price') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>

                                                        <div class="col-xl-3">
                                                            <label for="original_price" class="form-label">
                                                                Discount Price <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="text" class="form-control"  name="discount_price[]" placeholder="Enter Discount Price" value="<?= old('discount_price',$ba->discount_price) ?? '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g,'');">
                                                            @error('discount_price') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>

                                                        <!-- Buttons -->
                                                        <div class="col-md-3">
                                                            <div class="d-flex gap-2 mt-4">
                                                                <button type="button" class="btn btn-success addQuantity">+</button>
                                                                <button type="button" class="btn btn-danger removeQuantity">−</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @endforeach

                                            @else
                                                {{-- ADD MODE : empty row --}}
                                                <div class="row mt-5 quantity-row align-items-end" data-index="0">

                                                    <div class="col-xl-3">
                                                        <label class="form-label"> Unit <span class="text-danger"></span></label>
                                                        <select class="form-control select2"  name="unit[]">
                                                        <option value="">--select--</option>
                                                                <?php
                                                                    if (isset($unitData)) {
                                                                        foreach ($unitData as $val) { ?>
                                                                        <option <?=(old('unit', $unit) == $val->id)? 'selected':'' ?> value="<?php echo $val->id ?>"><?php echo ucwords($val->unit_name) ?></option>
                                                                <?php }
                                                                }
                                                                ?>

                                                        </select>

                                                        @error('unit') <span class="text-danger">{{$message}}</span> @enderror

                                                    </div>


                                                    <div class="col-xl-3">
                                                        <label for="original_price" class="form-label">
                                                            Original Price <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control"  name="original_price[]" placeholder="Enter Original Price" value="<?= old('original_price',$original_price) ?? '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g,'');">
                                                        @error('original_price') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    <div class="col-xl-3">
                                                        <label for="original_price" class="form-label">
                                                            Discount Price <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control"  name="discount_price[]" placeholder="Enter Discount Price" value="<?= old('discount_price',$discount_price) ?? '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g,'');">
                                                        @error('discount_price') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="d-flex gap-2 mt-4">
                                                            <button type="button" class="btn btn-success addQuantity">+</button>
                                                            <button type="button" class="btn btn-danger removeQuantity">−</button>
                                                        </div>
                                                    </div>

                                                </div>

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

    $(document).on("change",".select2",function(){
    $(this).next('.select2').find('.select2-selection').removeClass("input-error");
});

    document.getElementById('product_image').addEventListener('change', function() {

    if (this.files.length > 3) {
        alert("You can upload maximum 3 images only.");
        this.value = "";
    }

});

$(document).on('click', '.addQuantity', function () {

    let row = $(this).closest('.quantity-row');

    row.find('select.select2').each(function () {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy');
        }
    });

    let clone = row.clone();

    clone.find('input').val('');
    clone.find('select').val('');

    clone.find('.select2-container').remove();
    clone.find('select')
         .removeClass('select2-hidden-accessible')
         .removeAttr('data-select2-id')
         .removeAttr('aria-hidden')
         .removeAttr('tabindex');

    $('#quantityWrapper').append(clone);

    $('select.select2').select2({
        width: '100%'
    });
});

$(document).on('click', '.removeQuantity', function () {

    if ($('.quantity-row').length > 1) {
        $(this).closest('.quantity-row').remove();
    }
});



$(document).on('change', '#shop', function () {

    let isHotel = $(this).find(':selected').data('hotel');

    if (isHotel == 1) {
        $('#food_type_div').slideDown(200);
    } else {
        $('#food_type_div').slideUp(200);
        $('#food_type').val('');
    }

});




    $('#category').on('change', function () {
    let categoryId = $(this).val();

    $('#shop').html('<option value="">--select--</option>');

    if (categoryId) {
        $.ajax({
            url: "{{ route('getShopsByCategory') }}",
            type: "GET",
            data: { category_id: categoryId },
            success: function (data) {
                $.each(data, function (key, value) {
                  $('#shop').append(
            '<option value="'+ value.id +'" data-hotel="'+ value.is_hotel +'">'+
            value.shop_name +
            '</option>'
        );
                });
            }
        });
    }
});

$(document).ready(function () {

    let categoryId = $('#category').val();
    let selectedShop = "{{ old('shop', $shop ?? '') }}";


    $('#shop').empty().append('<option value="">Select Shop</option>');

    if (categoryId) {
        $.ajax({
            url: "{{ route('getShopsByCategory') }}",
            type: "GET",
            data: { category_id: categoryId },
            success: function (data) {

                $.each(data, function (key, value) {

                    let selected = (value.id == selectedShop) ? 'selected' : '';

                   $('#shop').append(
                        '<option value="' + value.id + '" data-hotel="' + value.is_hotel + '" ' + selected + '>' +
                        value.shop_name +
                        '</option>'
                    );
                });

                $('#shop').trigger('change');
            }
        });
    }
});


   $("#productForm").on("submit", function (e) {

    let isValid = true;

    // remove old errors
    $(".error-msg").remove();

    let category = $(this).find('select[name="category"]').val();
    let shop = $(this).find('select[name="shop"]').val();
    let product_name = $(this).find('input[name="product_name"]').val();
    let product_description = $(this).find('textarea[name="product_description"]').val().trim();



    let imageInput = document.getElementById("product_image");
    let hasOldImage = $("#has_old_product_image").val();

    if (imageInput.files.length > 3) {
        isValid = false;
        $("#product_image").after('<span class="text-danger error-msg">Maximum 3 images allowed</span>');
    }

    if (imageInput.files.length == 0 && hasOldImage == 0) {
        isValid = false;
        $("#product_image").after('<span class="text-danger error-msg">Please upload product image</span>');
    }

    if (category == '') {
        isValid = false;
        $(this).find('select[name="category"]')
            .closest('.col-xl-4')
            .append('<span class="text-danger error-msg">Please select category</span>');
    }

    if (shop == '') {
        isValid = false;
        $(this).find('select[name="shop"]')
            .closest('.col-xl-4')
            .append('<span class="text-danger error-msg">Please select shop</span>');
    }

    if (product_name == '') {
        isValid = false;
        $(this).find('input[name="product_name"]').after('<span class="text-danger error-msg">Please enter product name</span>');
    }

    if (product_description == '') {
        isValid = false;
        $(this).find('textarea[name="product_description"]')
            .after('<span class="text-danger error-msg">Please enter product description</span>');
    }

    // Validate quantity rows
$(".quantity-row").each(function () {

    let unit = $(this).find('select[name="unit[]"]');
    let originalPrice = $(this).find('input[name="original_price[]"]');
    let discountPrice = $(this).find('input[name="discount_price[]"]');

    // remove old error
    unit.next('.select2').find('.select2-selection').removeClass("input-error");
    originalPrice.removeClass("input-error");
    discountPrice.removeClass("input-error");

    if (unit.val() == '') {
        isValid = false;
        unit.next('.select2').find('.select2-selection').addClass("input-error");
    }

    if (originalPrice.val() == '') {
        isValid = false;
        originalPrice.addClass("input-error");
    }

    if (discountPrice.val() == '') {
        isValid = false;
        discountPrice.addClass("input-error");
    }

});

    if (!isValid) {
        e.preventDefault();
    }

});


 </script>
 @endsection
