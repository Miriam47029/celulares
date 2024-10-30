<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marca = Marca::all();
        return response()->json($marca);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = ['marca' => 'required|string|min:1|max:100'];
        $validator = \Validator::make($request->input(),$rules);
        if ($validator->fails()) {
            return response()->json([
            'status' => false,
            'errors' => $validator->errors()->all()
            ],400);
            
        }
        $marca = new Marca($request->input());
        $marca->save();
        return response()->json([
            'status' => true,
            'message' => 'Marca creada exitosamente'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        return response()->json(['status' => true, 'data' => $marca]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
        $rules = ['marca' => 'required|string|min:1|max:100'];
        $validator = Validator::make($request->input(),$rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' =>$validator->errors()->all()
            ],400);
        }
        $marca->update($request->input());
        return response()->json([
            'status' => true,
            'message' => 'Marca actualizada exitosamente'
        ],200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        $marca->delete();
        return response()->json([
            'status' => true,
            'message' => 'Marca eliminada exitosamente'
        ],200); 
    }
}
