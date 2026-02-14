@extends('backend.app_template')
@section('title','Direct Orders  List')
@section('content')
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="d-flex align-items-center mt-2 mb-2">
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Direct Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>

            </div>

        </div>

        <div class="row">

            <table id="datatables" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Order Date </th>
                        <th>Customer Name</th>
                        <th>Shop Name </th>
                        <th>Image</th>
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
                                <td><?= date('d-m-Y', strtotime($row->created_at)) ?></td>
                                <td><?= optional($row->userData)->name ?? '-' ?></td>
                                <td><?= optional($row->shopData)->shop_name ?? '-' ?></td>
                                <td>
                                    <a href="<?= $row->image_url ?>" target="_blank">
                                        <img src="<?= $row->image_url ?>" height="50" width="50">
                                    </a>
                                </td>
                                <td>
                                    <a data-placement="top" title="Status" data-original-title="Status" href="javascript:void(0)" onclick="changeOrderStatus('<?php echo $row->id ?>','<?php echo ($row->status == 1) ? 0 : 1 ?>','Order')" class="badge bg-pill bg-<?php echo ($row->status == 1) ? 'success' : 'danger' ?>">
                                            <?php echo ($row->status == 1) ? '' : 'New Order' ?>
                                    </a>
                                </td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" href="javascript:void(0)" onclick="commonDelete('<?php echo $row->id ?>','User')" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>

                     <?php $i++;
                                            }?>

                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
