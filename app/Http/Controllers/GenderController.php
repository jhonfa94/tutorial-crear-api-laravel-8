<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //LISTAR GENEROS
        $genders = Gender::all();
        return response()->json([
            'message' => 'OK',
            'data' => $genders
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Regla de validación
        $rules = [
            'name' => 'required|string|max:15'
        ];
        //Usamos el Facade Validator para validar nuestra regla respecto a los datos recibidos en Request
        $validator = Validator::make($request->all(), $rules);
        //Si falla la validacion retornamos los errores
        if ($validator->fails()) {
            return $validator->errors();
        }
        //Instancia modelo Gender
        $newGender = new Gender;
        //Llevanos el modelo con los datos del Request
        $newGender->name = $request->name;
        //Guardamos
        if ($newGender->save()) {
            return response()->json([
                'message' => 'Registro exitoso',
                'data' => $newGender
            ], 200);
        } else {
            return response()->json([
                'message' => 'Error al guardar el registro',
                'data' => false
            ], 400);
        }
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
    public function update(Request $request, $id)
    {
        //Regla de validación
        $rules = [
            'name' => 'required|string|max:15'
        ];
        //Usamos el Facade Validator para validar nuestra regla respecto a los datos recibidos en Request
        $validator = Validator::make($request->all(), $rules);
        //Si falla la validacion retornamos los errores
        if ($validator->fails()) {
            return $validator->errors();
        }
        //buscamos el género con el id enviado por la URL
        $gender = Gender::find($id);

        if ($gender) {
            //Cambiamos el nombre del género con el valor enviado por Request
            $gender->name = $request->name;
            //Actualizamos y retornamos el género con el nuevo valor
            if ($gender->update()) {
                return response()->json([
                    'message' => 'Registro actualizado con exito',
                    'data' => $gender
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Error al actualizar el registro',
                    'data' => false
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'EL género no existe',
                'data' => false
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //buscamos el género con el id enviado por la URL
        $gender = Gender::find($id);
        if ($gender) {
            //Buscamos si hay Macotas relacionadas con este Género
            $pet = Pet::where('id_gender', $gender->id)->get();
            //Si no encontramos mascotas borramos, de lo contrario no
            if ($pet->count() < 1) {
                if ($gender->delete()) {
                    return response()->json([
                        'message' => 'Registro eliminado con exito',
                        'data' => $gender
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Error al eliminar el registro',
                        'data' => false
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'No se puede eiliminar el registro existen mascotas con este género asignado',
                    'data' => false
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'EL genero no existe',
                'data' => false
            ], 400);
        }
    }
}
