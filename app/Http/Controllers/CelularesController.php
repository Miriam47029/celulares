<?php

namespace App\Http\Controllers;

use App\Models\Celulares;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Models\Marca;

class CelularesController extends Controller
{
    // Método para listar celulares con paginación
    public function index()
{
    $celulares = Celulares::select('celulares.*', 'marcas.marca as marca')
        ->join('marcas', 'marcas.id', '=', 'celulares.marca_id')
        ->get(); // Usamos get() para obtener todos los registros sin paginación

    // Modificar la respuesta para incluir la URL completa de la imagen
    $celulares->transform(function ($celular) {
        $celular->foto = $celular->foto ? url('storage/' . $celular->foto) : null; // Asegurarse de que la URL esté completa
        return $celular;
    });

    return response()->json($celulares);
}

    

    // Método para crear un nuevo celular
    public function store(Request $request)
{
    $rules = [
        'modelo' => 'required|string|max:80',
        'descripcion' => 'required|string|max:255',
        'precio' => 'required|numeric|min:0|max:99999.99',
        'camara' => 'required|numeric|min:5|max:108',
        'marca_id' => 'required|exists:marcas,id',
        'foto' => 'required|image|mimes:jpeg,png,jp|max:2048'
    ];

    $validator = \Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()->all()
        ], 400);
    }

    // Guardar la imagen en el almacenamiento público
    if ($request->hasFile('foto')) {
        $fileName = time() . '.' . $request->foto->extension();
        $filePath = $request->file('foto')->storeAs('imagenes_celulares', $fileName, 'public');
    }

    // Crear el nuevo registro con la ruta de la imagen guardada
    $celular = Celulares::create([
        'modelo' => $request->modelo,
        'descripcion' => $request->descripcion,
        'precio' => $request->precio,
        'camara' => $request->camara,
        'marca_id' => $request->marca_id,
        'foto' => $filePath // Guardar la ruta de la imagen
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Celular creado satisfactoriamente',
        'data' => $celular
    ], 200);
}

    // Método para mostrar un celular específico
    public function show($id)
    {
        $celular = Celulares::find($id);

        if (!$celular) {
            return response()->json([
                'status' => false,
                'message' => 'El celular seleccionado es inválido'
            ], 404);
        }

        return response()->json(['status' => true, 'data' => $celular]);
    }

    // Método para actualizar un celular existente
    public function update(Request $request, $id)
    {
        // Verificar si el celular existe
        $celular = Celulares::find($id);

        if (!$celular) {
            return response()->json([
                'status' => false,
                'message' => 'El celular seleccionado es inválido'
            ], 404);
        }

        // Reglas de validación
        $rules = [
            'modelo' => 'required|string|max:80',
            'descripcion' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0|max:99999.99',
            'camara' => 'required|numeric|min:5|max:108',
            'marca_id' => 'required|exists:marcas,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Campo opcional para la imagen
        ];

        // Validar los datos recibidos
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        // Si se sube una nueva imagen
        if ($request->hasFile('foto')) {
            // Eliminar la imagen anterior si existe
            if ($celular->foto) {
                Storage::disk('public')->delete($celular->foto);
            }

            // Subir la nueva imagen
            $fileName = time() . '.' . $request->foto->extension();
            $filePath = $request->file('foto')->storeAs('imagenes_celulares', $fileName, 'public');
            $celular->foto = $filePath; // Actualizar el campo foto con la nueva ruta
        }

        // Actualizar los otros campos
        $celular->update($request->except('foto')); // Se excluye la imagen si no fue enviada

        return response()->json([
            'status' => true,
            'message' => 'Celular actualizado correctamente',
            'data' => $celular
        ], 200);
    }

    // Método para eliminar un celular
    public function destroy($id)
    {
        $celular = Celulares::find($id);

        if (!$celular) {
            return response()->json([
                'status' => false,
                'message' => 'El celular seleccionado es inválido'
            ], 404);
        }

        // Eliminar la imagen si existe
        if ($celular->foto) {
            Storage::disk('public')->delete($celular->foto);
        }

        $celular->delete();

        return response()->json([
            'status' => true,
            'message' => 'Celular eliminado correctamente'
        ], 200);
    }

    // Método para obtener la cantidad de celulares por marca
    public function CelularesByMarca()
    {
        $celulares = Celulares::select(DB::raw('count(celulares.id) as count, marcas.marca'))
            ->rightJoin('marcas', 'marcas.id', '=', 'celulares.marca_id')
            ->groupBy('marcas.marca')
            ->get();

        return response()->json($celulares);
    }

    // Método para listar todos los celulares sin paginación
    public function all()
    {
        $celulares = Celulares::select('celulares.*', 'marcas.marca as marca')
            ->join('marcas', 'marcas.id', '=', 'celulares.marca_id')
            ->get();

        return response()->json($celulares);
    }
}
