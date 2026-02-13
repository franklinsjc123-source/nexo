@extends('backend.app_template')
@section('title','Product List')
@section('content')
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="d-flex align-items-center mt-2 mb-2">
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Product</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>

            </div>

        </div>

        <div class="row">
            <div class="d-flex justify-content-end mb-4">
                <a href="<?php echo route('addProduct') ?>" class="btn btn-primary">Add Product</a>
            </div>

            <table id="datatables" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Category</th>
                        <th>Shop Name</th>
                        <th>Product Name</th>
                        <th>Quantity (unit)</th>
                        <th>Original Price</th>
                        <th>Discount Price</th>
                        <th>Product Image</th>
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
                                <td><?= optional($row->categoryData)->category_name ?? '-' ?></td>
                                <td><?= optional($row->shopData)->shop_name ?? '-' ?></td>
                                <td><?= $row->product_name ?></td>
                                <td>
                                    {{ $row->qty }}{{ optional($row->unitData)->unit_name ? ' ('.$row->unitData->unit_name.')' : '' }}
                                </td>                                <td><?= $row->original_price ?></td>
                                <td><?= $row->discount_price ?></td>

                                <td> <img class="mt-2" src="<?= $row->product_image ?>" alt="image description" width="50" height="50"></td>

                                <td><a data-placement="top" title="Status" data-original-title="Status" href="javascript:void(0)" onclick="changeStatus('<?php echo $row->id ?>','<?php echo ($row->status == 1) ? 0 : 1 ?>','Product')" class="badge bg-pill bg-<?php echo ($row->status == 1) ? 'success' : 'danger' ?>">
                                            <?php echo ($row->status == 1) ? 'Active' : 'In-Active' ?></a>
                                </td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="Edit" href="<?php echo route('addProduct',[$row->id]) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i></a>
                                    <a data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" href="javascript:void(0)" onclick="commonDelete('<?php echo $row->id ?>','Product')" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></a>
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
