@extends('backend.app_template')
@section('title','Orders  Report')
@section('content')
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="d-flex align-items-center mt-2 mb-2">
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>

            </div>

        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">

            <h6 class="mb-0 flex-grow-1"></h6>

            <div class="d-flex gap-2">
                
                <a href="javascript:void(0)"
                    class="btn btn-success btn-sm excelBtn"
                    data-table="order_report"
                    title="Export to Excel">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                </a>

                <a href="javascript:void(0)"
                    class="btn btn-success btn-sm pdfBtn"
                    data-table="order_report"
                    title="Export to PDF">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
            </div>

        </div>

        <div class="card mt-4 shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('orders-report') }}">
                    @csrf
                    <div class="row align-items-end g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">From Date</label>
                            <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">To Date</label>
                            <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                        </div>

                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-search me-1"></i> Search
                                </button>
                                <a href="{{ route('orders-report') }}" class="btn btn-light px-4">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-5">

            <table id="order_report" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Order Date </th>
                        <th>Order ID </th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Payment Type</th>
                        <th>Order Amount</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                     <?php
                        $i = 0;
                            foreach ($records as $key => $row) {
                            ?>
                            <tr>
                                <td><?php echo $i + 1 ?></td>
                                <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                                <td><?php echo $row->order_id ?></td>
                                <td><?php echo $row->customerData->name ?? '-' ?></td>
                                <td><?php echo $row->customerData->email ?? '-' ?></td>
                                <td><?php echo $row->payment_type ?></td>
                                <td><?php echo $row->amount ?></td>
                                <td>
                                    <?php
                                        if ($row->order_status == 1) {
                                            $class = "warning";
                                            $text  = "New Order";
                                        } elseif ($row->order_status == 2) {
                                            $class = "success";
                                            $text  = "Delivered";
                                        } elseif ($row->order_status == 3) {
                                            $class = "danger";
                                            $text  = "Cancelled";
                                        }  elseif ($row->order_status == 4) {
                                            $class = "secondary";
                                            $text  = "Dispatched";
                                        } else {
                                            $class = "secondary";
                                            $text  = "Unknown";
                                        }
                                    ?>

                                    <a href="javascript:void(0)"
                                        class="badge bg-<?php echo $class; ?> " data-id="<?= $row->id ?>" data-status="<?= $row->order_status ?>"> <?php echo $text; ?>
                                    </a>
                                </td>

                            </tr>

                     <?php $i++;
                            }?>

                </tbody>
            </table>
        </div>
        <!-- Submit Section -->
    </div>
</main>
@endsection
