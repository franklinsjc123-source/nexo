<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
   public function store(Request $request)
{
    $validated = $request->validate([
        'request_id' => 'required|string',
        'service_id' => 'required|integer',
        'service_request_type_id' => 'required|integer',
        'number' => 'nullable|string|max:50',
    ]);

    $data = ServiceRequest::create($validated);
    sendRequestNotification('findflciker', 'Contact notification from findflicker');

    return response()->json([
        'status' => true,
        'message' => 'Service request created',
        'data' => $data
    ]);
}


public function update(Request $request, $id)
{
    $validated = $request->validate([
        'service_id' => 'sometimes|integer',
        'service_request_type_id' => 'sometimes|integer',
        'number' => 'sometimes|string|max:50',
    ]);

    $data = ServiceRequest::findOrFail($id);
    $data->update($validated);

    return response()->json([
        'status' => true,
        'message' => 'Service request updated',
        'data' => $data
    ]);
}
}
