<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function export() 
    {

        $resultados = DB::select("SELECT *
        FROM Partido, ActaVotos, VotosPartido
        WHERE Partido.id = VotosPartido.idPartido and 
            ActaVotos.id = VotosPartido.idPartido ");
            
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
}
