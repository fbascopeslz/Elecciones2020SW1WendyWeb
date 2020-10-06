<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\ResultadoGrafica;



class GraficasController extends Controller
{    
    public function listarResultadosNacionalesGraficas() 
    {
        $arrayResultados = null;

        $SQL = "SELECT P.nombre as NombrePartido, P.sigla as SiglaPartido,
                        P.colorHex as ColorHex, SUM(VP.cantidadVotosPartido) as VotosTotales
                FROM Partido as P, VotosPartido as VP, ActaVotos as A 
                WHERE VP.idPartido = P.id and VP.idActaVotos = A.id 
                GROUP BY P.nombre, P.sigla, P.colorHex";                     

        $arrayResultados = DB::select($SQL);             

        return $arrayResultados;        
    }

}
