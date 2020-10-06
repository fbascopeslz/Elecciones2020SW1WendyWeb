<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Rol;


class UsuarioController extends Controller
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
            $usuarios = User::join('Rol', 'Usuario.idRol', '=', 'Rol.id')
            ->select('Usuario.id', 'Usuario.login', 'Usuario.nombres', 
                'Usuario.apellidos', 'Usuario.ci', 'Usuario.telefono', 
                'Usuario.correo', 'Usuario.estado', 'Usuario.idRol', 'Rol.nombre as rol')
            ->orderBy('Usuario.id', 'desc')->paginate(5);
        } else {
            $usuarios = User::join('Rol', 'Usuario.idRol', '=', 'Rol.id')
            ->select('Usuario.id', 'Usuario.login', 'Usuario.nombres', 
                'Usuario.apellidos', 'Usuario.ci', 'Usuario.telefono', 
                'Usuario.correo', 'Usuario.estado', 'Usuario.idRol', 'Rol.nombre as rol')
            ->where('Usuario.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('Usuario.id', 'desc')->paginate(5);        
        }                

        return [
            'pagination' => [
                'total' => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page' => $usuarios->perPage(),
                'last_page' => $usuarios->lastPage(),
                'from' => $usuarios->firstItem(),
                'to' => $usuarios->lastItem(),
            ],
            'usuarios' => $usuarios
        ];
    }

    
    public function store(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }              
                        
        $usuario = new User();        
        $usuario->login = $request->login;

        if ($usuario->idRol == 1) { //Es delegado de mesa
            $usuario->password = md5($request->password);
        } else { //Es administrador
            $usuario->password = bcrypt($request->password);
        }
        
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->ci = $request->ci;
        $usuario->telefono = $request->telefono;
        $usuario->correo = $request->correo;        
        
        $usuario->idRol = $request->idRol;

        $usuario->save();       
    }

    
    public function update(Request $request)
    {   
        
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $usuario = User::findOrFail($request->id);
        $usuario->login = $request->login;

        if ($usuario->idRol == 1) { //Es delegado de mesa
            $usuario->password = md5($request->password);
        } else { //Es administrador
            $usuario->password = bcrypt($request->password);
        }
        
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->ci = $request->ci;
        $usuario->telefono = $request->telefono;
        $usuario->correo = $request->correo;        
        
        $usuario->idRol = $request->idRol;        
                        
        $usuario->save();       
                      
    }

        
    public function desactivar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $usuario = User::findOrFail($request->id);
        $usuario->estado = 0; //false        
        $usuario->save();
    }


    public function activar(Request $request)
    {
        //Si la peticion no es de Ajax redirige a la ruta '/'
        if (!$request->ajax()) {
            return redirect('/');
        }

        $usuario = User::findOrFail($request->id);
        $usuario->estado = 1; //true
        $usuario->save();
    }


    public function selectRol(Request $request){
        $roles = Rol::select('id', 'nombre', 'descripcion')
        ->orderBy('id', 'asc')
        ->get();
        return ['roles' => $roles];
    }
}
