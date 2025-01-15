<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos=Producto::all();
        return response()->json([
            "datos"=> $datos,
        ],200
    );
    }

    public function store(ProductoRequest $request)
    {
        $datosProductos = $request->all();
        $imagen = $request->file('foto');
        if ($imagen && $imagen->isValid()) {
            $rutaCarpeta = 'storage/uploads';
            $nombreImagen = $imagen->getClientOriginalName();
            $request->file('foto')->move($rutaCarpeta, $nombreImagen);
            $datosProductos['foto'] = $nombreImagen;
        }

        $producto = Producto::create($datosProductos);

        return response()->json([
            "producto"=> $producto,
        ],200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::where('ID', '=', $id)->first();
        return response()->json([
            "producto"=> $producto,
        ],200
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('producto.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        try {
            $producto = Producto::where('ID', '=', $id)->first();

            if (!$producto) {
                return response()->json(['message' => 'Producto no encontrado.'], 404);
            }

            $validatedData = $request->validate([
                'nombre_producto' => 'sometimes|string|max:100',
                'descripcion' => 'sometimes|string|max:255',
                'proveedor' => 'sometimes|exists:proveedores,ID',
                'categoria' => 'sometimes|exists:categorias,ID',
                'cantidad_en_stock' => 'sometimes|numeric',
                'precio' => 'sometimes|numeric',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:2048',
            ]);

            if ($request->nombre_producto)
                $producto->nombre_producto = $request->nombre_producto;

            if ($request->descripcion)
                $producto->descripcion = $request->descripcion;

            if ($request->proveedor)
                $producto->proveedor = $request->proveedor;

            if ($request->categoria)
                $producto->categoria = $request->categoria;

            if ($request->cantidad_en_stock)
                $producto->cantidad_en_stock = $request->cantidad_en_stock;

            if ($request->precio)
            $producto->precio = $request->precio;
            
            if ($request->hasFile('foto')) {
                $imagen = $request->file('foto');
                $rutaCarpeta = 'storage/uploads';
                $nombreImagen = $imagen->getClientOriginalName();
                $request->file('foto')->move($rutaCarpeta, $nombreImagen);
                $producto->foto = $nombreImagen;
            }

            $producto->save();

            return response()->json(['producto' => $producto, 'message' => 'Producto actualizado correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al actualizar el producto. '.$e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Producto::where('ID','=',$id)->delete();
        return response()->json(['message' => 'Producto borrado correctamente.'], 200);
    }
}