@extends('backend.app_template')
@section('title','Company List')
@section('content')

<main class="app-wrapper">
    <div class="container-fluid">

        <div class="d-flex align-items-center mt-2 mb-2">
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Company</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>

            </div>

        </div>

        <div class="row">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0 flex-grow-1">Company</h5>
                <div class="d-flex gap-2 ">

                </div>
            </div>
           <div class="table-responsive mt-5">
               <table   id="datatables" class="table table-nowrap table-hover table-bordered w-100 mt-5 ">

                    <?php
                    if (isset($records)) {?>
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Company Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
                        $i = 1;
                            foreach ($records as $key => $row) {
                            ?>
                                <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $row->company_name ?></td>
                                    <td><?php echo $row->phone ?></td>
                                    <td><?php echo $row->email ?></td>


                                    <td>
                                        <a data-toggle="tooltip" data-placement="top" title="Edit" href="<?php echo route('addCompany',[$row->id]) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i></a>

                                    </td>
                                </tr>

                            <?php $i++;
                            }   ?>

                </tbody>
                <?php
                    }?>
            </table>
                </div>
        </div>
        <!-- Submit Section -->
    </div>
</main>





@endsection
