<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProveedorRequest;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller {
    public function index()
    {
        $datos=Proveedor::all();
        return response()->json([
            "datos"=> $datos,
        ],200
    );
    }

    public function store(ProveedorRequest $request)
    {
        $datosproveedors = $request->all();
        $imagen = $request->file('foto');
        if ($imagen && $imagen->isValid()) {
            $rutaCarpeta = 'storage/uploads';
            $nombreImagen = $imagen->getClientOriginalName();
            $request->file('foto')->move($rutaCarpeta, $nombreImagen);
            $datosproveedors['foto'] = $nombreImagen;
        }

        $proveedor = Proveedor::create($datosproveedors);

        return response()->json([
            "proveedor"=> $proveedor,
        ],200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $proveedor = Proveedor::where('ID', '=', $id)->first();
        return response()->json([
            "proveedor"=> $proveedor,
        ],200
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedor.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        try {
            $proveedor = Proveedor::where('ID', '=', $id)->first();

            if (!$proveedor) {
                return response()->json(['message' => 'proveedor no encontrado.'], 404);
            }

            $validatedData = $request->validate([
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:2048',
                'nombre_empresa' => 'string|max:100',
                'nombre_contacto' => 'string|max:50',
                'telefono' => 'string|max:15',
                'correo' => 'string|max:100',
                'productos_suministrados' => 'string',
            ]);

            if ($request->foto)
                $proveedor->foto = $request->foto;

            if ($request->nombre_empresa)
                $proveedor->nombre_empresa = $request->nombre_empresa;

            if ($request->nombre_contacto)
                $proveedor->nombre_contacto = $request->nombre_contacto;

            if ($request->telefono)
                $proveedor->telefono = $request->telefono;

            if ($request->correo)
                $proveedor->correo = $request->correo;

            if ($request->productos_sumistrados)
                $proveedor->productos_sumistrados = $request->productos_sumistrados;

            if ($request->hasFile('foto')) {
                $imagen = $request->file('foto');
                $rutaCarpeta = 'storage/uploads';
                $nombreImagen = $imagen->getClientOriginalName();
                $request->file('foto')->move($rutaCarpeta, $nombreImagen);
                $proveedor->foto = $nombreImagen;
            }

            $proveedor->save();

            return response()->json(['proveedor' => $proveedor, 'message' => 'proveedor actualizado correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al actualizar el proveedor. '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Proveedor::where('ID','=',$id)->delete();
        return response()->json(['message' => 'Proveedor borrado correctamente.'], 200);
    }
}
