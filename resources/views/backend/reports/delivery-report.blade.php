@extends('backend.app_template')
@section('title','Delivery Report')
@section('content')

<style>
    .select2-container {
    width: 100% !important;
}

.select2-container--open {
    z-index: 9999 !important; /* Below modal (1050) */
}
</style>

<main class="app-wrapper">
    <div class="container-fluid">

        <div class="d-flex align-items-center mt-2 mb-2">
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Delivery Report (<?= date('Y') ?>)</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 flex-grow-1"></h6>
            <div class="d-flex gap-2">
                <a href="javascript:void(0)"
                    class="btn btn-success btn-sm excelBtn"
                    data-table="delivery_report"
                    title="Export to Excel">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                </a>

                <a href="javascript:void(0)"
                    class="btn btn-success btn-sm pdfBtn"
                    data-table="delivery_report"
                    title="Export to PDF">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
            </div>
        </div>

        <div class="card mt-4 shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('delivery-report') }}">
                    @csrf
                    <div class="row align-items-end g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Month</label>
                            <select class="form-control select2" name="month">
                                <option value="">All Months</option>
                                <?php for($m=1; $m<=12; $m++) { ?>
                                    <option value="<?= $m ?>" <?= (request()->has('month') ? request('month') : date('m')) == $m ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Delivery Person</label>
                            <select class="form-control select2" name="delivery_person_id">
                                <option value="">All</option>
                                @foreach($delivery_persons as $person)
                                    <option value="<?= $person->id ?>" <?= request('delivery_person_id') == $person->id ? 'selected' : '' ?>><?= $person->name ?></option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-search me-1"></i> Search
                                </button>
                                <a href="<?= route('delivery-report') ?>" class="btn btn-light px-4">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-5">
            <table id="delivery_report" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Order Date </th>
                        <th>Order ID </th>
                        <th>Customer</th>
                        <th>Delivery Person</th>
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
                            <td><?= $i + 1 ?></td>
                            <td><?= date('d-m-Y h:i A', strtotime($row->created_at)) ?></td>
                            <td><?= $row->order_id ?></td>
                            <td>
                                <?= optional($row->customerData)->name ?? '-' ?>
                            </td>
                            <td>
                                <?= optional($row->deliveryPerson)->name ?? '-' ?>
                            </td>
                            <td><?= $row->payment_type ?></td>
                            <td>
                                <?php
                                  if (Auth::user()->auth_level == 4) {
                                        echo number_format(round($row->final_shop_total), 2);
                                    } else {
                                        echo number_format(round($row->amount + $row->ship_amount), 2);
                                    }
                                ?>
                            </td>
                            <td>
                                <span class="badge bg-success">Delivered</span>
                            </td>
                        </tr>
                     <?php $i++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>






<script>
    $(document).ready(function() {
        $('.select2').select2();



</script>

@endsection
