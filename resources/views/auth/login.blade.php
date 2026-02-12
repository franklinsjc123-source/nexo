@extends('auth.template')
@section('content')
<body>
<!-- START -->
<div >
    <img src="<?= asset('backend_assets') ?>/images/auth/login_bg.jpg" alt="" class="auth-bg light w-full h-full opacity-60 position-absolute top-0">
    <img src="<?= asset('backend_assets') ?>/images/auth/auth_bg_dark.jpg" alt="" class="auth-bg d-none dark">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-10">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card mx-xxl-8">
                    <div class="card-body py-12 px-8">
                        <img src="<?= asset('backend_assets') ?>/images/logo.jpg" alt="" height="60" class="mb-4 mx-auto d-block">
                        <form method="POST" action="<?= route('authLogin') ?>">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="username" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="Enter your Email" >
                                    @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span> <i onclick="showPasswd()" class="bi bi-eye ms-1 fs-16"></i></label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" >
                                     @error('password') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                        <!-- <div class="form-text">
                                            <a href="javascript:void(0)" class="link link-primary text-muted text-decoration-underline">Forgot password?</a>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col-12 mt-8">
                                    <button type="submit" class="btn btn-primary w-full mb-4">Sign In<i class="bi bi-box-arrow-in-right ms-1 fs-16"></i></button>
                                </div>
                            </div>

                        </form>
                        <div class="text-center">
                        </div>
                    </div>
                </div>
                <p class="position-relative text-center fs-12 mb-0">© 2026 NEXO CART</p>
            </div>
        </div>
    </div>
</div>
<script>
    function showPasswd()
    {
          var  inputType = $('#password');
          (inputType.prop('type') === 'password') ? $('#password').prop('type','text'):$('#password').prop('type', 'password');
    }
     setInterval(function () {
        fetch('/refresh-session');
    }, 1 * 60 * 1000);
</script>
@endsection
