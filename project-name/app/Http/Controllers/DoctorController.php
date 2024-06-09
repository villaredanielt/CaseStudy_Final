<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::all();
        return response()->json(['doctors'=>$doctors, 'method'=>'get']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max: 255',
            'email' => 'required|string|email|max:255|unique:doctors',
            'specialization' => 'required|string|max: 255'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $doctor = Doctor::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'specialization' => $request->input('specialization'),
        ]);

        return response()->json(['message' => 'Doctor created successfully', 'data' => $doctor], 201);
    }

    public function show($id)
    {
        $doctors = Doctor::findOrFail($id);
        return response()->json(['doctors'=>$doctors, 'method'=>'get']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'sometimes|required|string|email|max:255|unique:doctors,email',
            'specialization' =>'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $doctor = Doctor::findOrFail($id);
        $doctor->update([
            'name' => $request->name,
            'email' => $request->email,
            'specialization' => $request->specialization
        ]);

        return response()->json(['doctor' => $doctor,'message' => 'Doctor Info Updated Sucessfuly'], 201);
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return response()->json(['message' => 'Doctor info deleted successfully']);
    }
}


