@extends('backend.app_template')
@section('title','Profile')
@section('content')
 <?php

    $id                  = isset($record->id) ? $record->id : '';
    $name                = isset($record->name) ? $record->name : '';
    $email               = isset($record->email) ? $record->email : '';
    $mobile              = isset($record->mobile) ? $record->mobile : '';
    $first_name          = isset($record->first_name) ? $record->first_name : '';
    $last_name           = isset($record->last_name) ? $record->last_name : '';
    $address            = isset($record->address1) ? $record->address1 : '';
    $city                = isset($record->city) ? $record->city : '';
    $state               = isset($record->state) ? $record->state : '';
    $pincode             = isset($record->pincode) ? $record->pincode : '';


    ?>
<main class="app-wrapper">
        <div class="container-fluid">

            <div class="d-flex align-items-center mt-2 mb-2">
                <h6 class="mb-0 flex-grow-1">Profile</h6>
                <div class="flex-shrink-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="mb-6">
                        <ul class="nav nav-pills" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Profile</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="information-tab" data-bs-toggle="tab" data-bs-target="#information-tab-pane" type="button" role="tab" aria-controls="information-tab-pane" aria-selected="true">Credentials</button>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                           <div class="card">
                                <div class="card-body p-6">
                                    <form action="<?=  route('save-profile') ?>" method="POST" class="py-4">
                                         @csrf
                                        <input type="hidden" name="id" value="<?= $id ?>" />
                                        <div class="row g-5">
                                            <div class="col-lg-4">
                                                <div class="tab-pane fade show active" id="html-label-input-required" role="tabpanel" aria-labelledby="html-label-input-required-tab" tabindex="0">
                                                    <label for="labelInputRequired" class="form-label">Name<span class="text-danger ms-1">*</span></label>
                                                    <input type="text" placeholder="Enter Your Name" class="form-control" id="labelInputRequired" value="<?= $name ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="inputExample26" class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                                <div class="form-icon">
                                                    <input type="email" class="form-control" id="inputExample26" name="email" value="<?= $email ?>" placeholder="example@gmail.com">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="form-label" for="contact-number">Contact Number</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-secondary-200" id="basic-addon1">+91</span>
                                                    <input type="text" class="form-control" id="contact-number"   value="<?= $mobile ?>"  name="mobile" placeholder="123-456-7890">
                                                </div>
                                            </div>
                                           
                                            <!-- <div class="col-lg-4">
                                                <label for="addressInputLayout4" class="form-label">Address<span class="text-danger ms-1">*</span></label>
                                                <div class="form-group">
                                                    <input class="form-control form-control-icon" id="addressInputLayout4" placeholder="Address" required="">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="zipCodeLayout1" class="form-label">Zip Code<span class="text-danger ms-1">*</span></label>
                                                <div class="form-icon">
                                                    <i class="ri-map-pin-2-line text-muted"></i>
                                                    <input type="text" class="form-control form-control-icon" id="zipCodeLayout1" placeholder="Zip Code" required>
                                                </div>
                                            </div> -->
                                           
                                            <div class="col-lg-12 d-flex justify-content-end gap-2 flex-shrink-0 mt-8">
                                                <button type="button" class="btn btn-light-dark text-body">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="information-tab-pane" role="tabpanel" aria-labelledby="information-tab" tabindex="0">
                            <div class="card">
                                <div class="card-body p-6">
                                    <form action="<?=  route('change-password') ?>" method="POST" class="py-4">
                                        <div class="row g-5">
                                            <div class="col-lg-4">
                                                <div class="tab-pane fade show active" id="html-label-input-required" role="tabpanel" aria-labelledby="html-label-input-required-tab" tabindex="0">
                                                    <label for="password" class="form-label">Password <span class="text-danger ms-1">*</span> <i class="bi bi-eye" onclick="showPassword('password')"></i></label>
                                                    <input type="password" placeholder="Enter Password" class="form-control" id="password" >
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="tab-pane fade show active" id="html-label-input-required" role="tabpanel" aria-labelledby="html-label-input-required-tab" tabindex="0">
                                                    <label for="cpassword" class="form-label">Confirm Password <span class="text-danger ms-1">*</span> <i class="bi bi-eye" onclick="showPassword('cpassword')"></i></label>
                                                    <input type="password" placeholder="Enter Confirm Password" id="cpassword" class="form-control" id="labelInputRequired" >
                                                </div>
                                            </div>
                                           
                                            <div class="col-lg-12 d-flex justify-content-end gap-2 flex-shrink-0 mt-8">
                                                <button type="button" class="btn btn-light-dark text-body">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!--End container-fluid-->
    </main><!--End app-wrapper-->
    <script>
        function showPassword(id)
    {
        var inputType = $('#'+id);
        (inputType.prop('type') === 'password') ? $('#'+id).prop('type','text'):$('#'+id).prop('type', 'password');
    }
    </script>
    @endsection