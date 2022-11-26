<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaEmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int @idEmpresa
     * @return \Illuminate\Http\Response
     */
    public function index($idEmpresa)
    {
        //
        $empresa = Empresa::find($idEmpresa);

        if (!$empresa)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una empresa con ese código.'])],404);
        }
        $empleados = $empresa->empleados;
        return response()->json(['status'=>'ok','data'=>$empresa],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $idEmpresa
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $idEmpresa)
    {
        //
        // Insertamos un empleado de la empresa $idEmpresa
        // Necesitaremos el empresa_id que lo recibimos en la RUTA api/empresas/{idEmpresa}/empleados
        //        # id (auto incremental)
        //        # nombre
        //        # edad

        // Primero comprobaremos si estamos recibiendo todos los campos. Nos los da $request
        if ( !$request->input('nombre') || !$request->input('edad'))
        {
            // Se devuelve un array errors con los errores encontrados y
            // cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el proceso de alta de empleado.'])],422);
        }

        // Buscamos la empresa.
        $empresa= Empresa::find($idEmpresa);
        // Si no existe la empresa que le hemos pasado mostramos otro código de error de no encontrado.
        if (!$empresa)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una empresa con ese código.'])],404);
        }

        // Si la empresa existe entonces lo almacenamos.
        // Insertamos una fila en Empleados con create pasándole todos los datos recibidos.
        $nuevoEmpleado=$empresa->empleados()->create($request->all());

        // Más información sobre respuestas en http://jsonapi.org/format/
        // Devolvemos el código HTTP 201 Created – [Creada] Respuesta a un POST que resulta en una creación. Debería ser combinado con un encabezado Location, apuntando a la ubicación del nuevo recurso.
        return response()->json(['data'=>$nuevoEmpleado], 201)->header('Location',  url('/').'/empleados/'.$nuevoEmpleado->id);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idEmpresa, $idEmpleado)
    {
        //
        $empresa = Empresa::find($idEmpresa);

        if (!$empresa) {
            return response()->json(['errors' => array(['code' => 404, 'message' => 'No se encuentra una empresa con ese código.'])], 404);
        }

        $empleado = $empresa->empleados()->find($idEmpleado);

        // Si no existe ese empleado devolvemos un error.
        if (!$empleado) {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors' => array(['code' => 404, 'message' => 'No se encuentra un empleado con ese código asociado al club.'])], 404);
        }

        // Listado de campos recibidos teóricamente.
        $nombre = $request->input('nombre');
        $edad = $request->input('edad');


        // Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
        // El método de la petición se sabe a través de $request->method();
        /*  Modelo      Longitud        Capacidad       Velocidad       Alcance */
        if ($request->method() === 'PATCH') {
            // Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
            $bandera = false;

            // Actualización parcial de campos.
            if ($nombre) {
                $empleado->nombre = $nombre;
                $bandera = true;
            }
            if ($edad) {
                $empleado->edad = $edad;
                $bandera = true;
            }
// Se revisarían todos los campos
            if ($bandera) {
                // Almacenamos en la base de datos el registro.
                $empleado->save();
                return response()->json(['status' => 'ok', 'data' => $empleado], 200);
            } else {
                // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
                // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
                return response()->json(['errors' => array(['code' => 304, 'message' => 'No se ha modificado ningún dato del empleado.'])], 304);
            }

        }
        // Si el método no es PATCH entonces es PUT y tendremos que actualizar todos los datos. Revisar que estén TODOS LOS DATOS NECESARIOS.
        if (!$nombre || !$edad ) {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['errors' => array(['code' => 422, 'message' => 'Faltan valores para completar el procesamiento.'])], 422);
        }

        $empleado->nombre = $nombre;
        $empleado->edad = $edad;

        // Almacenamos en la base de datos el registro.
        $empleado->save();

        return response()->json(['status' => 'ok', 'data' => $empleado], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $idEmpresa
     * @param int $idEmpleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($idEmpresa, $idEmpleado)
    {
        //
        // Comprobamos si la empresa que nos están pasando existe o no.
        $empresa = Empresa::find($idEmpresa);

        // Si no existe esa empresa devolvemos un error.
        if (!$empresa)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una empresa con ese código.'])],404);
        }

        // La empresa existe entonces buscamos el empleado que queremos borrar asociado a esa empresa.
        $empleado = $empresa->empleados()->find($idEmpleado);

        // Si no existe ese empleado devolvemos un error.
        if (!$empleado)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un empleado con ese código asociado a esa empresa.'])],404);
        }

        // Procedemos por lo tanto a eliminar el empleado.
        $empleado->delete();
        // Se usa el código 204 No Content – [Sin Contenido] Respuesta a una petición exitosa que no devuelve un body (como una petición DELETE)
        // Este código 204 no devuelve body así que si queremos que se vea el mensaje tendríamos que usar un código de respuesta HTTP 200.
        return response()->json(['code'=>204,'message'=>'Se ha eliminado el empleado correctamente.'],204);
    }

}
