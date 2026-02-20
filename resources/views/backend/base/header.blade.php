<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-layout="vertical">
<head>

    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Admin & Dashboards Template" name="description" />
    <meta content="Pixeleyez" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- layout setup -->
    <script type="module" src="<?= asset('backend_assets') ?>/js/layout-setup.js"></script>

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= asset('backend_assets') ?>/images/fevi_icon.png">    <!-- Simplebar Css -->
    <link rel="stylesheet" href="<?= asset('backend_assets') ?>/libs/simplebar/simplebar.min.css">

       <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

     <link href="<?= asset('backend_assets') ?>/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <!-- Swiper Css -->
    <link href="<?= asset('backend_assets') ?>/libs/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= asset('backend_assets') ?>/libs/air-datepicker/air-datepicker.css">
    <!-- Nouislider Css -->
    <link href="<?= asset('backend_assets') ?>/libs/nouislider/nouislider.min.css" rel="stylesheet">
    <!-- Bootstrap Css -->
    <link href="<?= asset('backend_assets') ?>/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css">
    <!--icons css-->
    <link href="<?= asset('backend_assets') ?>/css/icons.min.css" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="<?= asset('backend_assets') ?>/css/app.min.css" id="app-style" rel="stylesheet" type="text/css">

    <link href="<?= asset('backend_assets') ?>/css/select2.min.css" id="app-style" rel="stylesheet" type="text/css">



    <script src="<?= asset('backend_assets') ?>/js/jquery.min.js"></script>
    <script src="<?= asset('backend_assets') ?>/libs/apexcharts/apexcharts.min.js"></script>
</head>

<div id="globalLoader">
    <div class="spinner"></div>
</div>

<body>
