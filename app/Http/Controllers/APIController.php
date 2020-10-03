<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Paquete;

use DB;


class APIController extends Controller
{
    public function login(Request $request) 
    {
        $login = $request->input("login");        
        $password = $request->input("password"); //password llega en md5

        $paquete = new Paquete();

        try {
            //idRol: 1 => Delegado de mesa
            $SQL = "SELECT *
                    FROM Usuario 
                    WHERE (login = '$login' OR correo = '$login')
                        and password = '$password' 
                        and idRol = 1";
            $array = DB::select($SQL);                    
            
            if ($array !== null && count($array) > 0) {
                $paquete->error = 0;
                $paquete->message = "Usuario encontrado";
                $paquete->values = $array[0];                    

                return response()->json(
                    $paquete
                );
            }        

            $paquete->error = 1;
            $paquete->message = "Usuario o Contraseña incorrectos";
            $paquete->values = null;
            return response()->json(
                $paquete
            );
            
        } catch (\Throwable $th) {
            $paquete->error = 2;
            $paquete->message = "Ocurrio un error. Porfavor intente de nuevo";
            $paquete->values = $th;        
        }

        return response()->json(
            $paquete
        );
    }


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
                        Mesa.codigo = $codigo and
                        Usuario.id = $idUsuario";
            $array = DB::select($sql);
            
            if ($array !== null && count($array) > 0) {                       
                //Comprobar si ya envio la imagen para el Acta de Votos    
                $sql = "SELECT ActaVotos.imagen, ActaVotos.hora, ActaVotos.fecha, 
                            ActaVotos.cantidadVotosTotal, ActaVotos.votosNulos, ActaVotos.votosBlancos  
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
                    $sql = "SELECT Mesa.codigo, Departamento.nombre as departamento, Provincia.nombre as provincia, 
                                Municipio.nombre as municipio, Localidad.nombre as localidad, Recinto.nombre as recinto
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
            $paquete->values = $th;        
        }

        return response()->json(
            $paquete
        );
    }


    public function procesarIdPartidos($arrayVotos, $idActaVotos)
    {
        $SQL = "SELECT id, sigla 
                FROM Partido";
        $query = DB::select($SQL);

        $array = [];
        
        //indice 0 al 2 son los VotosTotales, VotosNulos y VotosBlancos
        for ($i=3; $i < count($arrayVotos); $i++) { 
            for ($j=0; $j < count($query); $j++) { 
                if (strpos($query[$j]["sigla"], $arrayVotos[$i]["sigla"]) ) {                    
                    $array[] = ['idPartido' => $query[$j]["id"], 'idActaVotos' => $idActaVotos, 'cantVotosPartido' => $arrayVotos[$i]["votos"]];
                    break;
                }
            }
        }

        return $array;
    }
        
    public function procesarTextoImagen(Request $request)
    {
        $idUsuario = $request->input("idUsuario");
        $arrayVotos = json_decode($request->input("arrayVotos"), true);
        $urlImagen = $request->input("urlImagen");    

        $paquete = new Paquete();        

        try {        
            $idActaVotos = DB::table('ActaVotos')->insertGetId(
                ['cantTotal' => $arrayVotos[0]["votos"],                
                'votosBlanco' => $arrayVotos[1]["votos"],
                'votosNulos' => $arrayVotos[2]["votos"],
                'fecha' => date('Y-m-d'), 
                'hora' => date('H:i:s'),
                'imagen' => $urlImagen,
                'idUsuario' => $idUsuario,]               
            );


            //Buscar los ids de cada partido 
            //$array = $this->procesarIdPartidos($arrayVotos, $idActaVotos);
            $SQL = "SELECT id, sigla 
                FROM Partido";
            $query = DB::select($SQL);
            $array = [];                                   
            //indice 0 al 2 son los VotosTotales, VotosNulos y VotosBlancos
            for ($i=3; $i < count($arrayVotos); $i++) { 
                for ($j=0; $j < count($query); $j++) { 
                    if ($arrayVotos[$i]["sigla"] == $query[$j]->sigla) {                    
                        $array[] = ['idPartido' => $query[$j]->id, 'idActaVotos' => $idActaVotos, 'cantvotopartido' => $arrayVotos[$i]["votos"]];
                        break;
                    }
                }
            }


            if ($idActaVotos !== null) {
                DB::table('PartidoActaVotos')->insert(
                    $array
                );                    

                $paquete->error = 0;
                $paquete->message = "Datos procesados correctamente";
                $paquete->values = null;

                return response()->json(
                    $paquete
                );
            } 
                    
            $paquete->error = 1;
            $paquete->message = "No se puede procesar el texto porfavor intente de nuevo";
            $paquete->values = null;
            return response()->json(
                $paquete
            );
            
        } catch (\Throwable $th) {
            $paquete->error = 1;
            $paquete->message = "Ocurrio un error. Porfavor intente de nuevo";
            $paquete->values = $th->getMessage();      
            return response()->json(
                $paquete
            );  
        }        
    }
    
}
