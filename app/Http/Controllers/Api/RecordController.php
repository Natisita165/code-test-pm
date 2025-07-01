<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        $query = Record::query();

        if ($request->has('status') && in_array($request->status, ['0', '1'], true)) {
            $query->where('status', (int) $request->status);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:100|unique:test,code',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'code.required' => 'El código es obligatorio.',
            'code.unique' => 'El código ya está en uso.',
            'status.required' => 'El estado es obligatorio.',
            'status.boolean' => 'El estado debe ser válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $record = Record::create([
            'uuid' => (string) Str::uuid(),
            'name' => $request->name,
            'description' => $request->description,
            'code' => $request->code,
            'status' => $request->status,
        ]);

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
            'code' => 'required|string|max:100|unique:test,code,' . $record->uuid . ',uuid',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'code.required' => 'El código es obligatorio.',
            'code.unique' => 'El código ya está en uso.',
            'status.required' => 'El estado es obligatorio.',
            'status.boolean' => 'El estado debe ser válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $record->update($request->only(['name', 'description', 'code', 'status']));

        return response()->json($record);
    }

    public function destroy($uuid)
    {
        $record = Record::findOrFail($uuid);
        $record->delete();

        return response()->json(null, 204);
    }
}