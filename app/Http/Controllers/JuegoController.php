<?php

namespace App\Http\Controllers;

use App\Models\Juego;
use Illuminate\Http\Request;

class JuegoController extends Controller
{
    //
    public function index() {

        return response()->json(['code' => 200, 'status'=>'ok', 'data' => Juego::all()]);
    }

    /**
     * Muestra un juego, pero por ID...
     */
    public function show($id) {

        $id = Juego::find($id);
        
        if(!$id)
            return response()->json(['code' => 200, 'status'=>'noOk', 'data' => ""], 404);

        return response()->json(['code' => 200, 'status'=>'ok', 'data' => Juego::find($id)]);

    }

    /**
     * Inserta una nueva fila en la BD.
     */
    public function store(Request $request) {

        //$idJuego = Juego::find();

        //Si no existe el juego, lo creamos.

        if(Juego::where("codigo", "=", $request->codigo)->first()) {
            return response()->json(
                [
                    'code' => 406,
                    'status'=>'noOk',
                    "errorMSG" => "El codigo ya existe",
                    'data' => ""
                ],
                406
            );
        }
        
        $juego = new Juego();

        $juego->codigo = $request->codigo;
        $juego->nombre = $request->nombre;

        $juego->save();

        //$nuevoEmpleado = $idJuego->create($request->all());
        
        return response($request);
    }

    /**
     * Actualiza una fila
     * @param request Datos a modificar
     * @param codJuego codigo a buscar
     */
    public function update(Request $request, $codJuego) {

        if(!Juego::where("codigo", "=", $codJuego)->first()) {
            return response()->json(
                [
                    'code' => 404,
                    'status'=>'noOk',
                    "errorMSG" => "El codigo no existe",
                    'data' => ""
                ],
                404
            );
        }

        Juego::where("codigo", "=", $request->codigo)->update([
            //"codigo" => $request -> codigo,
            "nombre" => $request -> nombre
        ]);

        return response()->json(
            [
                'code' => 200,
                'status'=>'Ok',
                "data" => "Juego modificado correctamente",
            ],
            200
        );
    }

    /**
     * Elimina un juego a partir de un codigo...
     */
    public function destroy($codigo) {

        if(!Juego::where("codigo", "=", $codigo)->first()) {
            return response()->json(
                [
                    'code' => 404,
                    'status'=>'noOk',
                    "errorMSG" => "El codigo no existe",
                    'data' => ""
                ],
                404
            );
        }

        Juego::where("codigo", $codigo)->delete();

        return response()->json(
            [
                'code' => 200,
                'status'=>'Ok',
                "data" => "Juego eliminado correctamente",
            ],
            200
        );

    }

}
