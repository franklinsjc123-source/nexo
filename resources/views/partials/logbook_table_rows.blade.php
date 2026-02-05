@forelse($records as $row)
<tr data-date="{{ $row->log_date }}">
    <td><input type="checkbox" class="row-check" value="{{ $row->id }}"></td>
    <td>{{ $loop->iteration }}</td>
    <td>{{ \Carbon\Carbon::parse($row->log_date)->format('d-m-Y') }}</td>
    <td>{{ $row->companies->full_name;}}</td>
    <td>{{ $row->routeInfo?->route_name ?? '-' }}</td>
    <td>{{ $row->starting_place }}</td>
    <td>{{ $row->starting_km }}</td>
    <td>{{ $row->closing_place }}</td>
    <td>{{ $row->closing_km }}</td>
    <td>{{ $row->running_km }}</td>
    <td>{{ $row->diff_km }}</td>
    <td>{{ $row->drivers->driver_name ?? '-' }}</td>
    <td>
        <a href="javascript:void(0)" class="btn btn-sm btn-warning"
           onclick="openlogBookModal('{{ $row->id }}')">
            <i class="bi bi-pencil-fill"></i>
        </a>

        <a href="javascript:void(0)" class="btn btn-sm btn-danger"
           onclick="commonDelete('{{ $row->id }}','LogBookEntry')">
            <i class="bi bi-trash-fill"></i>
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="12" class="text-center">No records found</td>
</tr>
@endforelse
