<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Collection;

class ServiceRequestController extends Controller
{
    private function respond($data, Request $request, $status = 200)
    {
        // XML response
        if ($request->header('Accept') === 'application/xml') {

            // If collection (GET all)
            if ($data instanceof Collection) {
                $array = [
                    'request' => $data->map(function ($item) {
                        return $item->toArray();
                    })->toArray()
                ];
                $xml = ArrayToXml::convert($array, 'requests');
            }
            // If single record
            else {
                $xml = ArrayToXml::convert(
                    ['request' => is_array($data) ? $data : $data->toArray()],
                    'requests'
                );
            }

            return response($xml, $status)
                ->header('Content-Type', 'application/xml');
        }

        // Default JSON response
        return response()->json($data, $status);
    }

    // GET /requests
    public function index(Request $request)
    {
        return $this->respond(ServiceRequest::all(), $request);
    }

    // POST /requests
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'address' => 'required|string',
            'issue_type' => 'required|string',
            'status' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $data = ServiceRequest::create($validated);

        return $this->respond([
            'message' => 'Service request created successfully',
            'data' => $data
        ], $request, 201);
    }

    // PUT /requests/{id}
    public function update(Request $request, $id)
    {
        $data = ServiceRequest::find($id);

        if (!$data) {
            return $this->respond(['error' => 'Request not found'], $request, 404);
        }

        $data->update($request->all());

        return $this->respond([
            'message' => 'Service request updated',
            'data' => $data
        ], $request);
    }

    // DELETE /requests/{id}
    public function destroy(Request $request, $id)
    {
        $data = ServiceRequest::find($id);

        if (!$data) {
            return $this->respond(['error' => 'Request not found'], $request, 404);
        }

        $data->delete();

        return $this->respond(['message' => 'Request deleted'], $request);
    }
}
