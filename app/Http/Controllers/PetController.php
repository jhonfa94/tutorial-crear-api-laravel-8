<?php

namespace App\Http\Controllers;


use App\Models\Gender;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($limit = null)
    {
        //Si mandamos un limit lo asignamos y si no por defecto sera de 5 el paginado
        $limit ? $limit = $limit : $limit = 5;
        //Buscamos las mascotas y hacemos join con las respectivas tablas y retornamos paginado el resultado
        $pets = Pet::select('pets.id', 'pets.name as pet', 'pets.age', 'users.name as user', 'genders.name as gender')
            ->join('users', 'pets.id_user', '=', 'users.id')
            ->join('genders', 'pets.id_gender', '=', 'genders.id')
            ->OrderBy('id', 'desc')->paginate($limit);

        return response()->json([
            'message' => 'ok',
            'data' => $pets,
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
            'name'      =>  'required|string',
            'age'       =>  'required|numeric',
            'gender'    =>  'required|numeric'
        ];
        //Validamos
        $validator = Validator::make($request->all(), $rules);
        //Retorna si falla la validación
        if ($validator->fails()) {
            return $validator->errors();
        }
        //Comprobamos que exista el género enviado
        $gender = Gender::find($request->gender);
        if ($gender) {
            //Instancia al modelo de Mascota
            $newPet = new Pet();
            $newPet->name       = $request->name;
            $newPet->age        = $request->age;
            $newPet->id_gender  = $gender->id;
            $newPet->id_user    = Auth::user()->id; //ID del usuario logeado
            //Guardamos
            if ($newPet->save()) {
                return response()->json([
                    "message" => "Registro exitoso",
                    "data" => $newPet
                ], 200);
            } else {
                return response()->json([
                    "message" => "Error al guardar el registro",
                    "data" => false
                ], 400);
            }
        } else {
            return response()->json([
                "message" => "El género enviado no existe",
                "data" => false
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
        $rules = [
            'name'      =>  'required|string',
            'age'       =>  'required|numeric',
            'gender'    =>  'required|numeric'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator->errors();
        }
        //Buscamos la mascota por el ID
        $pet = Pet::find($id);
        //Comprobamos que exista la mascota
        if ($pet) {
            //Buscamos y comprobamo que le género enviado exista
            $gender = Gender::find($request->gender);
            if ($gender) {
                //Remplazamos los datos de la mascota por los enviados por Request
                $pet->name       = $request->name;
                $pet->age        = $request->age;
                $pet->id_gender  = $gender->id;
                //Actualizamos
                if ($pet->update()) {
                    return response()->json([
                        "message" => "Registro actualizado con exito",
                        "data" => $pet
                    ], 200);
                } else {
                    return response()->json([
                        "message" => "Error al actualizar el registro",
                        "data" => false
                    ], 400);
                }
            } else {
                return response()->json([
                    "message" => "El género enviado no existe",
                    "data" => false
                ], 400);
            }
        } else {
            return response()->json([
                "message" => "La mascota no existe",
                "data" => false
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
        //Buscamos la mascota a elminar por el ID
        $pet = Pet::find($id);
        //Coprobamos que exista la mascota
        if ($pet) {
            //Borramos
            if ($pet->delete()) {
                return response()->json([
                    "message" => "Registro eliminado con exito",
                    "data" => false
                ], 200);
            } else {
                return response()->json([
                    "message" => "Error al eliminar el registro",
                    "data" => false
                ], 400);
            }
        } else {
            return response()->json([
                "message" => "El registro a eliminar no existe",
                "data" => false
            ], 400);
        }
    }
}
