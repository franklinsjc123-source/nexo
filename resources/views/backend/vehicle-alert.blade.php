@extends('backend.app_template')
@section('title',' Expriry Alert')
@section('content')
<main class="app-wrapper">
    <div class="container-fluid">

         <div class="row mt-5">
            <h5 class="mb-3 mt-5">FC Near Expiry</h4>
            <div class="card-body border shadow-lg rounded p-4 m-2">

                <div class="table-responsive">
                    <table id="datatable_fc" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Zonal</th>
                                <th>Vehicle</th>
                                <th>Company</th>
                                <th>Route</th>
                                <th>FC Expiry</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fcs as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->zoneDetails->zone_name  }}</td>
                                    <td>{{ $d->vehicleObj->vehicle_no  }}</td>
                                    <td>{{ $d->companyDetails->full_name  }}</td>
                                    <td>{{ $d->vehicleObj->routeDetails->route_name  }}</td>
                                    <td>{{ $d->due_date  ? \Carbon\Carbon::parse($d->due_date)->format('d-m-Y') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="row mt-5">
            <h5 class="mb-3 mt-5">Insurance Near Expiry</h4>
            <div class="card-body border shadow-lg rounded p-4 m-2">

                <div class="table-responsive">
                    <table id="datatable_ins" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Zonal</th>
                                <th>Vehicle</th>
                                <th>Company</th>
                                <th>Route</th>
                                <th>Insurance Expiry</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($insurances as $d)
                                <tr>
                                     <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->zoneDetails->zone_name  }}</td>
                                    <td>{{ $d->vehicleObj->vehicle_no  }}</td>
                                    <td>{{ $d->companyDetails->full_name  }}</td>
                                    <td>{{ $d->vehicleObj->routeDetails->route_name  }}</td>
                                    <td>{{ $d->ins_due_date  ? \Carbon\Carbon::parse($d->ins_due_date)->format('d-m-Y') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="row mt-5">
            <h5 class="mb-3 mt-5">Permit Near Expiry</h4>
            <div class="card-body border shadow-lg rounded p-4 m-2">


                <div class="table-responsive">
                    <table id="datatable_permit" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Zonal</th>
                                <th>Vehicle</th>
                                <th>Company</th>
                                <th>Route</th>
                                <th>Permit Expiry</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permits as $d)
                                <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->zoneDetails->zone_name  }}</td>
                                    <td>{{ $d->vehicleObj->vehicle_no  }}</td>
                                    <td>{{ $d->companyDetails->full_name  }}</td>
                                    <td>{{ $d->vehicleObj->routeDetails->route_name  }}</td>
                                    <td>{{ $d->permit_due_date  ? \Carbon\Carbon::parse($d->permit_due_date)->format('d-m-Y') : '-' }}</td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




        <div class="row mt-5">
            <h5 class="mb-3 mt-5">Driving License Near Expiry</h4>
            <div class="card-body border shadow-lg rounded p-4 m-2">
                <div class="table-responsive">
                    <table id="datatable_license" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Driver Code</th>
                                <th>Driver Name</th>
                                <th>DL Expiry</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dldata as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->driver_code }}</td>
                                    <td>{{ $d->driver_name }}</td>
                                    <td>{{ $d->dl_expiry  ? \Carbon\Carbon::parse($d->dl_expiry)->format('d-m-Y') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- <div class="row mt-3">
            <h5 class="mb-3">FC Near Expiry</h4>
           <div class="card-body border shadow-lg rounded p-4 m-2">

                <div class="table-responsive">
                    <table id="datatablesFc" class="table table-nowrap table-hover table-bordered w-100 mt-5 colum-search">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Driver Code</th>
                                <th>Driver Name</th>
                                <th>FC Expiry</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fcdata as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->driver_code }}</td>
                                    <td>{{ $d->driver_name }}</td>
                                    <td>{{ $d->vehicleObj->fc_expiry  ? \Carbon\Carbon::parse($d->vehicleObj->fc_expiry)->format('d-m-Y') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}





    </div>
</main>

<script>

    $(document).ready(function() {

    $('#datatable_ins, #datatable_permit, #datatable_fc,#datatable_license').DataTable({
        dom: 'Bfrtip'
    });

    $('.select2').select2();
});
</script>

@endsection
