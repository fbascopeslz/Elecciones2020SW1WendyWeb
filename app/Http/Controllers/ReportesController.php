<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;


class ReportesController extends Controller
{
    public function listarResultadosNacionales() 
    {
        $SQL = "SELECT P.nombre as NombrePartido, P.sigla as SiglaPartido, 
                    SUM(VP.cantidadVotosPartido) as VotosTotales
                FROM Partido as P, VotosPartido as VP, ActaVotos as A 
                WHERE VP.idPartido = P.id and VP.idActaVotos = A.id 
                GROUP BY P.nombre, P.sigla";

        $array = DB::select($SQL);

        return $array;
    }

    public function getDepartamentos()
    {
        $SQL = "SELECT Departamento.id, Departamento.nombre
                FROM Departamento";

        $array = DB::select($SQL);

        return $array;
    }

    public function listarResultadosDepartamentales(Request $request) 
    {
        $idDepartamento = $request->idDepartamento;

        $SQL = "SELECT P.nombre as NombrePartido, P.sigla as SiglaPartido, 
                    SUM(VP.cantidadVotosPartido) as VotosTotales
                FROM Partido as P, VotosPartido as VP, ActaVotos as A, Usuario as U, Mesa as M,
                    Recinto as R, Localidad as L, Municipio as Mun, Provincia as P, Departamento as D
                WHERE VP.idPartido = P.id and VP.idActaVotos = A.id and
                    A.idUsuario = U.id and U.idMesa = M.id and
                    M.idRecinto = R.id and R.idLocalidad = L.id and
                    L.idMunicipio = Mun.id and Mun.idProvincia = P.id and
                    P.idDepartamento = D.id and D.id = $idDepartamento            
                GROUP BY P.nombre, P.sigla";

        $array = DB::select($SQL);

        return $array;
    }

}
