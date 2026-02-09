@extends('backend.app_template')
@section('title','Assign Permission')
@section('content')
<?php

$id        = isset($record->id) ? $record->id : '';
$permission = isset($record->display_name) ? $record->display_name : '';
$menu       = isset($record->category) ? $record->category : '';

?>
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="d-flex align-items-center mt-2 mb-2">
            <h6 class="mb-0 flex-grow-1">Assign Permission</h6>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Assign Permission</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card-body">

                    <form method="post" id="select_user" action="<?= route('updatePermission') ?>">
                        @csrf
                        <div class="row row-sm">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?= $id ?>" />
                                    <p class="mg-b-10">users<span class="text-danger">*</span></p>
                                    <select onchange="getPermission(this.value)" class="form-control select2" name="user">
                                        <option value="">--select--</option>
                                        <?php
                                        if (isset($users)) {
                                            foreach ($users as $val) { ?>
                                                <option  value="<?= $val->id ?>"><?= ucwords($val->name) ?></option>
                                        <?php }
                                        }
                                        ?>
                                    </select>
                                    @error('user')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row row-sm mt-3 p-5">
                            <?php
                            if (isset($permissionArr)) {
                                foreach ($permissionArr as $key => $row) { ?>


                                     <div class="section-title bg-light mb-4 mt-2">
                                        <input type="checkbox" name="parent" value="" class="form-check-input parent-<?= $key ?>" id="customCheckcolor<?= $key ?>" onclick="checkAll('<?= $key ?>')">
                                        <label class="form-check-label" for="customCheckcolor<?= $key ?>"><?= $key ?></label>
                                    </div>

                                    <?php foreach ($row as $ky => $val) { ?>

                                        <div class="form-check mb-2 col-md-3 mt-2 ">
                                            <input type="checkbox" value="<?= $val['id'] ?>" name="permissions[]" id="child<?= $val['id'] ?>" class="form-check-input child-<?= $key ?>">
                                            <label class="form-check-label" for="child<?= $val['id'] ?>"><?= ucwords($val['permission_name']) ?></label>

                                             <br>



                                        </div>
                                    <?php  }
                                }
                            }
                            ?>

                        </div>
                        <div class="row row-sm">
                            <div class="col-md-1 mt-4">
                                <div class="form-group">
                                    <input type="submit" value="Save" class="btn btn-warning disableBtn" />
                                </div>
                            </div>
                            <div class="col-md-3 mt-4">
                                <div class="form-group">
                                    <a href="javascript:void(0)" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

        </div>
</div>
    </div><!--End container-fluid-->
</main><!--End app-wrapper-->
<script>
function checkAll(id){
            $('.child-'+id).each( (index,obj)=>{
                var cbox = $('.parent-' + id);
                var isChecked = cbox.prop("checked");
                $('.child-' + id).prop("checked", isChecked);
            });
        }

        function uncheckAllCheckboxes() {
                var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }

        function getPermission(val)
        {

            var url = "<?php echo url('/getPermission/'); ?>/" + val;
            $.ajax({
                type: 'GET',
                url: url,
                contentType: false,
                processData: false,
                success: function(data) {
                    uncheckAllCheckboxes();
                    data.forEach((permission)=>{
                            if(permission.name !='')
                            {
                            var cbox = $('.parent-' + permission.name);
                            $('#child' + permission.id).prop("checked",true);
                            }

                        })
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }






    $(document).ready(function() {

        $('#select_user').submit(function(event) {

            if ($('input[name="permissions[]"]:checked').length === 0) {

                alert('Please give at least one permission for user.');
                event.preventDefault();
            }

        });
    });

    </script>
@endsection
