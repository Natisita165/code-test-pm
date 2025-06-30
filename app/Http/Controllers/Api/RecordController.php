<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecordController extends Controller
{
    // public function index(Request $request)
    // {
    //     // Bonus: filtro condicional por estado
    //     $status = $request->query('status');
    //     $query = Record::query();

    //     if (in_array($status, ['active', 'inactive'])) {
    //         $query->where('status', $status === 'active' ? 1 : 0);
    //     }

    //     return response()->json($query->get());
    // }
    public function index()
    {
        return response()->json(\App\Models\Record::all());
    }
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:100|unique:records,code',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $record = Record::create($request->all());

        return response()->json($record, 201);
    }

    public function show($uuid)
    {
        $record = Record::findOrFail($uuid);
        return response()->json($record);
    }

    public function update(Request $request, $uuid)
    {
        $record = Record::findOrFail($uuid);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:100|unique:records,code,' . $record->uuid . ',uuid',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $record->update($request->all());

        return response()->json($record);
    }

    public function destroy($uuid)
    {
        $record = Record::findOrFail($uuid);
        $record->delete();

        return response()->json(null, 204);
    }
}