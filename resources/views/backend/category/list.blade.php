@extends('backend.app_template')
@section('title','Category List')
@section('content')
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="d-flex align-items-center mt-2 mb-2">
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Category</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>

            </div>

        </div>

        <div class="row">
            <div class="d-flex justify-content-end mb-4">
                <a href="<?php echo route('addCategory') ?>" class="btn btn-primary">Add Category</a>
            </div>

            <table id="datatables" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Category</th>
                        <th>Category Image</th>
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
                                <td><?= $row->category_name ?></td>
                                <td> <img class="mt-2" src="<?= $row->file_path ?>" alt="image description" width="80" height="80"></td>

                                <td><a data-placement="top" title="Status" data-original-title="Status" href="javascript:void(0)" onclick="changeStatus('<?php echo $row->id ?>','<?php echo ($row->status == 1) ? 0 : 1 ?>','Category')" class="badge bg-pill bg-<?php echo ($row->status == 1) ? 'success' : 'danger' ?>">
                                            <?php echo ($row->status == 1) ? 'Active' : 'In-Active' ?></a>
                                </td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="Edit" href="<?php echo route('addCategory',[$row->id]) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i></a>
                                    <a data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" href="javascript:void(0)" onclick="commonDelete('<?php echo $row->id ?>','Category')" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></a>
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
