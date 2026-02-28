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

                <a href="#" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Export to Excel">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
            </div>
            
        </div>

         <div class="row mt-5 align-items-end">

            <div class="col-md-9">
                <form method="POST" action="{{ route('direct-orders-report') }}">
                    @csrf

                    <div class="row">

                        <div class="col-md-4">
                            <label>From Date</label>
                            <input type="date" class="form-control" name="from_date" value="">
                        </div>

                         <div class="col-md-4">
                            <label>To Date</label>
                            <input type="date" class="form-control" name="to_date" value="">
                        </div>

                        <div class="col-md-2 mt-5">
                              <button class="btn btn-primary">  Search </button>
                        </div>

                    </div>


                </form>
            </div>
        </div>

        <div class="row mt-5">

            <table id="datatables" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Order Date </th>
                        <th>Order ID </th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Order Amount</th>

                    </tr>
                </thead>
                <tbody>
                     <?php
                        $i = 0;
                            foreach ($records as $key => $row) {
                            ?>
                            <tr>
                                <td><?php echo $i + 1 ?></td>
                                <td><?php echo '' ?></td>
                                <td><?php echo '' ?></td>
                                <td><?php echo '' ?></td>
                                <td><?php echo '' ?></td>

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
