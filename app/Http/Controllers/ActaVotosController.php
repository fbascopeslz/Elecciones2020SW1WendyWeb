<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ActaVotos;

use Illuminate\Support\Facades\DB;

use Cloudder;



class ActaVotosController extends Controller
{
    public function index(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            //return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $actaVotos = ActaVotos::orderBy('id', 'desc')->paginate(5);
        } else {
            $actaVotos = ActaVotos::where($criterio, 'like', '%'.$buscar.'%')->orderBy('id', 'desc')->paginate(3);         
        }                

        return [
            'pagination' => [
                'total' => $actaVotos->total(),
                'current_page' => $actaVotos->currentPage(),
                'per_page' => $actaVotos->perPage(),
                'last_page' => $actaVotos->lastPage(),
                'from' => $actaVotos->firstItem(),
                'to' => $actaVotos->lastItem(),
            ],
            'actaVotos' => $actaVotos
        ];
    }

    
    public function store(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        //Guardar imagen en Cloudinary
        $imagen = Cloudder::upload($request->imagen);
        $img = $imagen->getResult();
        $pathImagen = $img['url'];

        //return response()->json($pathImagen);
        
        //Guardar de forma local
        //$imageName = time().'.'.$request->imagen->getClientOriginalExtension();
        //$request->imagen->move(public_path('images'), $imageName);        
                
        if ($pathImagen) {
            $producto = new Producto();
            $producto->codigo = $request->codigo;
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precio = $request->precio;            
            $producto->image = $pathImagen;
            $producto->save();
        }

    }

    
    public function update(Request $request)
    {   
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $producto = Producto::findOrFail($request->id);
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
       
        //Pregunta si llego ese parametro en la request
        if ($request->imagen) {
            //Guardar imagen en Cloudinary
            $imagen = Cloudder::upload($request->imagen);
            $img = $imagen->getResult();
            $pathImagen = $img['url'];
            if ($pathImagen) {
                $producto->image = $pathImagen;
            }
        }            
        
        $producto->save();
                
        //return response()->json($request);
    }

        
    public function desactivar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $producto = Producto::findOrFail($request->id);
        $producto->estado = false;        
        $producto->save();
    }


    public function activar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $producto = Producto::findOrFail($request->id);
        $producto->estado = true;        
        $producto->save();
    }
    
}
