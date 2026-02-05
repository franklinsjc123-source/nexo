 @extends('backend.app_template')
@section('title','Company Setting')
@section('content')

 <main class="app-wrapper">
            <div class="container-fluid">

                <div class="d-flex align-items-center mt-2 mb-2">

                    <div class="flex-shrink-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-end mb-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Settings</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Company Setting</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <form method="POST" id="permitForm" action="{{route('storecompanySetting')}}" enctype="multipart/form-data">
                           @csrf
                            <div>
                                <div class="card">

                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Company Setting</h5>
                                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Back</a>
                                    </div>


                                    <div class="card-body">

                                        <div class="card-body border shadow-lg rounded p-4 m-2">


                                        <div class="row g-4 mb-4">

                                            <div class="col-xl-3">
                                                <label for="fc_expiry" class="form-label">Insurance Expiry<span class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name="insurance_expiry" id="insurance_expiry" value="{{ $data->insurance_expiry }}">
                                            </div>

                                             <div class="col-xl-3">
                                                <label for="fc_expiry" class="form-label">FC Expiry<span class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name="fc_expiry" id="fc_expiry" value="{{ $data->fc_expiry }}">
                                            </div>


                                            <div class="col-xl-3">
                                                <label for="fc_expiry" class="form-label">Permit Expiry<span class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name="permit_expiry" id="permit_expiry" value="{{ $data->permit_expiry }}">
                                            </div>

                                            <div class="col-xl-3">
                                                <label for="license_expiry" class="form-label">Driving License Expiry<span class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name="license_expiry" id="license_expiry" value="{{ $data->license_expiry }}">
                                            </div>



                                            </div>
                                        </div>



                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3 my-5">
                                <a href="{{route('dashboard')}}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </main>


@endsection
