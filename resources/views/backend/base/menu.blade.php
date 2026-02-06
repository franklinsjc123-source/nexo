<?php

use Illuminate\Support\Facades\Auth;
?>
<!-- begin::App -->
<div id="layout-wrapper">
    <!-- Begin Header -->
    <header class="app-header" id="appHeader">
        <div class="container-fluid w-100">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <div class="d-inline-flex align-items-center gap-5">
                        <a href="<?= route('dashboard') ?>" class="fs-18 fw-semibold">
                            <img height="30" class="header-sidebar-logo-default d-none" alt="Logo"
                                src="<?= asset('backend_assets') ?>/images/yogesh_travels.png">
                            <img height="30" class="header-sidebar-logo-light d-none" alt="Logo"
                                src="<?= asset('backend_assets') ?>/images/yogesh_travels.png">
                            <img height="30" class="header-sidebar-logo-small d-none" alt="Logo"
                                src="<?= asset('backend_assets') ?>/images/yogesh_travels.png">
                            <img height="30" class="header-sidebar-logo-small-light d-none" alt="Logo"
                                src="<?= asset('backend_assets') ?>/images/yogesh_travels.png">
                        </a>

                        <button type="button"
                            class="horizontal-toggle btn btn-light-light text-muted icon-btn fs-5 rounded-pill d-none"
                            id="toggleHorizontal">
                            <i class="ri-menu-2-line header-icon"></i>
                        </button>

                    </div>
                </div>
                <div class="flex-shrink-0 d-flex align-items-center gap-1">


                    {{-- <div class="dark-mode-btn" id="toggleMode">
                        <button class="btn header-btn active" id="lightModeBtn">
                            <i class="bi bi-brightness-high"></i>
                        </button>
                        <button class="btn header-btn" id="darkModeBtn">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </div> --}}
                    <div class="dropdown pe-dropdown-mega d-none d-md-block">
                        {{-- <button class="btn header-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                        </button> --}}
                        <div class="dropdown-menu dropdown-mega-md header-dropdown-menu pe-noti-dropdown-menu p-0">
                            <div class="p-3 border-bottom">
                                <h6 class="d-flex align-items-center mb-0">Notification
                                    <!-- <span class="badge bg-success rounded-circle align-middle ms-1">4</span> -->
                                </h6>
                            </div>
                            <div class="p-3">

                                <!-- <div class="noti-item">
                                    <img src="<?= asset('backend_assets') ?>/images/avatar/avatar-8.jpg" alt=""
                                        class="avatar-md">
                                    <div>
                                        <a href="javascript:void(0)" class="stretched-link">
                                            <h6 class="mb-1 text-muted"><strong
                                                    class="fw-semibold text-body">Donald</strong> liked your post</h6>
                                        </a>
                                        <p class="text-muted mb-0">Friday, 11:29 PM</p>
                                    </div>
                                    <a href="javascript:void(0)"
                                        class="position-absolute top-10 end-0 fs-18 z-1 link link-danger"><i
                                            class="bi bi-x"></i></a>
                                </div>                                                           -->

                            </div>
                        </div>
                    </div>


                    <div class="dropdown pe-dropdown-mega d-none d-md-block">
                        <button class="header-profile-btn btn gap-1 text-start" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="header-btn btn position-relative">
                                <img src="<?= asset('backend_assets') ?>/images/avatar/avatar-10.jpg" alt=""
                                    class="img-fluid rounded-circle">
                                <span
                                    class="position-absolute translate-middle badge border border-light rounded-circle bg-success"><span
                                        class="visually-hidden">unread messages</span></span>
                            </span>
                            <div class="d-none d-lg-block pe-2">
                                <span class="d-block mb-0 fs-13 fw-semibold"><?= Auth::user()->name ?> </span>

                            </div>
                        </button>
                        <div class="dropdown-menu dropdown-mega-sm header-dropdown-menu p-3">
                            <div class="border-bottom pb-2 mb-2 d-flex align-items-center gap-2">
                                <img src="<?= asset('backend_assets') ?>/images/avatar/avatar-10.jpg" alt=""
                                    class="avatar-md">
                                <div>
                                    <a href="javascript:void(0)">
                                        <h6 class="mb-0 lh-base"><?= Auth::user()->name ?> </h6>
                                    </a>
                                    <p class="mb-0 fs-13 text-muted"><?= Auth::user()->email ?></p>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-1 border-bottom pb-1">
                                <li><a class="dropdown-item" href="<?= route('profile') ?>"><i
                                            class="bi bi-person me-1"></i> View Profile</a></li>
                                <?php  if(Auth::user()->auth_level  == 1 ) {  ?>
                                    {{-- <li><a class="dropdown-item" href="<?= route('assign-permission') ?>"><i class="bi bi-lock me-1"></i> Assign Permission</a></li> --}}
                                <?php  } ?>
                                <!-- <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-award me-1"></i> Subscription</a></li> -->
                            </ul>
                            <!-- <ul class="list-unstyled mb-1 border-bottom pb-1">
                                <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-clock me-1"></i> ChangLog</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-people me-1"></i> Team</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-headset me-1"></i> Support</a></li>
                            </ul> -->
                            <ul class="list-unstyled mb-0">
                                <li><a class="dropdown-item" href="<?= route('logout') ?> "><i
                                            class="bi bi-box-arrow-right me-1"></i> Sign Out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <button class="btn d-lg-none ms-2" id="mobileMenuBtn">
    <i class="ri-menu-line fs-22"></i>
</button>
    </header>

    <style>



    </style>

    <!-- END Header -->


    <aside class="pe-app-sidebar" id="sidebar">
        <div class="pe-app-sidebar-logo px-6 d-flex align-items-center position-relative">
            <!--begin::Brand Image-->
            <a href="<?= route('dashboard') ?>" class="fs-18 fw-semibold">
                <img height="30" class="pe-app-sidebar-logo-default d-none" alt="Logo"
                    src="<?= asset('backend_assets') ?>/images/yogesh_travels.png">
                <img height="30" class="pe-app-sidebar-logo-light d-none" alt="Logo"
                    src="<?= asset('backend_assets') ?>/images/yogesh_travels.png">
                <img height="30" class="pe-app-sidebar-logo-minimize d-none" alt="Logo"
                    src="<?= asset('backend_assets') ?>/images/yogesh_travels.png">
                <img height="30" class="pe-app-sidebar-logo-minimize-light d-none" alt="Logo"
                    src="<?= asset('backend_assets') ?>/images/yogesh_travels.png">
                <!-- FabKin -->
            </a>
            <!--end::Brand Image-->
        </div>
        <nav class="pe-app-sidebar-menu nav nav-pills" data-simplebar id="sidebar-simplebar">
            <ul class="pe-main-menu list-unstyled">

                <li class="pe-slide pe-has-sub">
                    <a href="<?= route('dashboard') ?>" class="pe-nav-link">
                        <i class="  bi bi-house-door-fill pe-nav-icon"></i>
                        <span class="pe-nav-content">Dashboards </span>
                    </a>
                </li>



                <li class="pe-slide pe-has-sub active">

                    <a href="#collapseLogistics" class="pe-nav-link" data-bs-toggle="collapse"
                        aria-expanded="true"
                        aria-controls="collapseLogistics">
                        <i class="bi bi-gear-fill pe-nav-icon"></i>
                        <span class="pe-nav-content">Settings</span>
                        <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
                    </a>

                    <ul class="pe-slide-menu collapse " id="collapseLogistics">

                        <li class="slide pe-nav-content1">
                            <a href="javascript:void(0)">Settings</a>
                        </li>

                         <li class="pe-slide-item">
                            <a href="<?= route('company') ?>" class="pe-nav-link
                                    @if(request()->routeIs(['company', 'addCompany'])) active @endif">
                                Company Setttings
                            </a>
                        </li>




                        <li class="pe-slide-item">
                            <a href="<?= route('pincode') ?>" class="pe-nav-link
                                    @if(request()->routeIs(['pincode', 'addPincode'])) active @endif">
                                Pincode
                            </a>
                        </li>


                         <li class="pe-slide-item">
                            <a href="<?= route('slider') ?>" class="pe-nav-link
                                    @if(request()->routeIs(['slider', 'addSlider'])) active @endif">
                                Slider
                            </a>
                        </li>


                        @if(auth()->check() && auth()->user()->hasPermission('User-Management'))
                            <li class="pe-slide-item">
                                <a href="<?= route('users') ?>" class="pe-nav-link
                                        @if(request()->routeIs(['user', 'addUser'])) active @endif">
                                    User Management
                                </a>
                            </li>
                        @endif

                         @if(auth()->check() && auth()->user()->hasPermission('permission'))
                          <li class="pe-slide-item">
                            <a href="<?= route('permission') ?>" class="pe-nav-link
                                     @if(request()->routeIs(['permission', 'addPermission'])) active @endif">
                                Permission
                            </a>
                        </li>
                        @endif


                        <li class="pe-slide-item">
                            <a href="<?= route('assign-permission') ?>" class="pe-nav-link
                                    @if(request()->routeIs(['assign-permission'])) active @endif">
                               Assign Permission
                            </a>
                        </li>

                    </ul>
                </li>




                <li class="pe-slide pe-has-sub">

                    <a href="#collapseLogistics-mas" class="pe-nav-link" data-bs-toggle="collapse"
                        aria-expanded="false"
                        aria-controls="collapseLogistics">
                        <i class="bi bi-bag-fill pe-nav-icon"></i>
                        <span class="pe-nav-content">Product Master</span>
                        <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
                    </a>

                    <ul class="pe-slide-menu collapse " id="collapseLogistics-mas">

                        <li class="slide pe-nav-content1">
                            <a href="javascript:void(0)">Product Masters</a>
                        </li>

                        @if(auth()->check() && auth()->user()->hasPermission('Route'))
                            <li class="pe-slide-item">
                                <a href="<?= route('category') ?>" class="pe-nav-link
                                        @if(request()->routeIs(['category', 'addCategory'])) active @endif">
                                    Category
                                </a>
                            </li>
                         @endif


                        @if(auth()->check() && auth()->user()->hasPermission('Driver'))
                         <li class="pe-slide-item">
                            <a href="<?= route('shop') ?>" class="pe-nav-link
                                @if(request()->routeIs(['shop', 'addShop'])) active @endif">
                                Shop
                            </a>
                        </li>
                        @endif


                        @if(auth()->check() && auth()->user()->hasPermission('Vehicle'))
                        <li class="pe-slide-item">
                            <a href="<?= route('product') ?>" class="pe-nav-link
                                @if(request()->routeIs(['product', 'addProduct'])) active @endif">
                                Product
                            </a>
                        </li>


                        @endif




                    </ul>
                </li>


                   <li class="pe-slide pe-has-sub">
                    <a href="#" class="pe-nav-link">
                        <i class="bi  bi-percent pe-nav-icon"></i>
                        <span class="pe-nav-content">Offers </span>
                    </a>
                </li>



                   <li class="pe-slide pe-has-sub">
                    <a href="#" class="pe-nav-link">
                        <i class="bi bi-cart-fill pe-nav-icon"></i>
                        <span class="pe-nav-content">Orders </span>
                    </a>
                </li>


                   <li class="pe-slide pe-has-sub">
                    <a href="#" class="pe-nav-link">
                        <i class="bi bi-person-fill pe-nav-icon"></i>
                        <span class="pe-nav-content">Delivery Person </span>
                    </a>
                </li>







                <li class="pe-slide pe-has-sub mobile-only">
                    <a href="<?= route('logout') ?>" class="pe-nav-link">
                        <i class="bi bi-box-arrow-right pe-nav-logout"></i>
                        <span class="pe-nav-content">Logout</span>
                    </a>
                </li>


            </ul>
        </nav>
    </aside>

    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>


<script>
const menuBtn = document.getElementById("mobileMenuBtn");
const sidebar = document.getElementById("sidebar");
const backdrop = document.getElementById("sidebar-backdrop");

menuBtn?.addEventListener("click", () => {
    sidebar.classList.add("show");
    backdrop.classList.add("show");
});

backdrop?.addEventListener("click", () => {
    sidebar.classList.remove("show");
    backdrop.classList.remove("show");
});
</script>

