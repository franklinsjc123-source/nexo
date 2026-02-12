@extends('backend.app_template')
@section('title','Offers List')
@section('content')
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="d-flex align-items-center mt-2 mb-2">
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Offers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>

            </div>

        </div>

        <div class="row">
            <div class="d-flex justify-content-end mb-4">
                <a href="<?php echo route('addOffer') ?>" class="btn btn-primary">Add Offer</a>
            </div>

            <table id="datatables" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Shop</th>
                        <th>Offer Code</th>
                        <th>Expiry Date</th>
                         <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                     <?php
                        $i = 0;
                            foreach ($records as $key => $row) {
                            ?>
                            <tr>
                                <td><?php echo $i + 1 ?></td>
                                <td><?= $row->shopData->shop_name ?></td>
                                <td><?= $row->offer_code ?></td>
                                <td><?= date('d-m-Y', strtotime($row->expiry_date)) ?></td>
                                <td><a data-placement="top" title="Status" data-original-title="Status" href="javascript:void(0)" onclick="changeStatus('<?php echo $row->id ?>','<?php echo ($row->status == 1) ? 0 : 1 ?>','Offers')" class="badge bg-pill bg-<?php echo ($row->status == 1) ? 'success' : 'danger' ?>">
                                            <?php echo ($row->status == 1) ? 'Active' : 'In-Active' ?></a>
                                </td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="Edit" href="<?php echo route('addOffer',[$row->id]) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i></a>
                                    <a data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" href="javascript:void(0)" onclick="commonDelete('<?php echo $row->id ?>','Offers')" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></a>
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
