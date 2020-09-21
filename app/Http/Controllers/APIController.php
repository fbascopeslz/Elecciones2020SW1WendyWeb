<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Paquete;

use DB;


class APIController extends Controller
{
    public function verificarCodigoMesa(Request $request)
    {
        $codigo = $request->input("codigo");
        $idUsuario = $request->input("idUsuario");

        $paquete = new Paquete();

        try {
            //Comprobar si el delegado esta asignado a esa mesa con dicho codigo
            $sql = "SELECT * 
                    FROM Mesa, Usuario
                    WHERE Usuario.idMesa = Mesa.id and
                        Mesa.codigo = $codigo
                        Usuario.id = $idUsuario";
            $array = DB::select($sql);
            
            if ($array !== null && count($array) > 0) {       
                //Comprobar si ya envio la imagen para el Acta de Votos    
                $sql = "SELECT *
                        FROM ActaVotos, Usuario
                        WHERE ActaVotos.idUsuario = Usuario.id and
                            Usuario.id = $idUsuario";
                $array = DB::select($sql);

                if ($array !== null && count($array) > 0) {
                    //Ya se envio la imagen
                    //Mostrar la imagen
                    $paquete->error = 0;
                    $paquete->message = "Usted ya envio la imagen para esta mesa";
                    $paquete->values = $array[0];                     
                } else {
                    //Aun no se envio la imagen                    
                    $sql = "SELECT Mesa.codigo, Departamento.nombre, Provincia.nombre, 
                                Municipio.nombre, Localidad.nombre, Recinto.nombre 
                            FROM Departamento, Provincia, Municipio, Localidad, Recinto, Mesa, Usuario
                            WHERE Departamento.id = Provincia.idDepartamento and
                                Provincia.id = Municipio.idProvincia and
                                Municipio.id = Localidad.idMunicipio and
                                Localidad.id = Recinto.idLocalidad and
                                Mesa.idRecinto = Recinto.id and
                                Usuario.idMesa = Mesa.id and
                                Usuario.id = " . $idUsuario;
                    $array = DB::select($sql);

                    //Mostrar informacion de la mesa
                    $paquete->error = 3;
                    $paquete->message = "Confirme la ubicacion de la mesa";
                    $paquete->values = $array[0];
                }                                               
            } else {               
                $paquete->error = 2;
                $paquete->message = "Usted no es el delegado asignado a esta mesa";
                $paquete->values = null;
            }
            
            return response()->json(
                $paquete
            );   

        } catch (\Throwable $th) {
            $paquete->error = 1;
            $paquete->message = "Ocurrio un error. Porfavor intente de nuevo";
            $paquete->values = null;        
        }

        return response()->json(
            $paquete
        );
    }

    
}
