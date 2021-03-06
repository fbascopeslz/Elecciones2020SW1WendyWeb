@extends('auth.contenido')

@section('login')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-group mb-0">
            <div class="card p-4">
                <form class="form-horizontal was-validated" method="POST" action="{{ route('login') }}">
                    @csrf {{-- Proteger de solicitudes de falsificacion atraves de sitios Cross Site--}}
                    <div class="card-body">
                        <h1>Acceder</h1>
                        <p class="text-muted">Control de acceso al sistema</p>
                        <div class="form-group mb-3 {{$errors->has('login' ? 'is-invalid' : '')}}"> {{--Si el usuario es invalido se agregara la clase is-invalid al div--}}
                            <span class="input-group-addon"><i class="icon-user"></i></span>
                            <input type="text" value="{{old('login')}}" name="login" id="login" class="form-control" placeholder="Usuario">
                            {!!$errors->first('login', '<span class="invalid-feedback">:message</span>')!!} {{--Motrar los errores(si ocurre) en el span--}}
                        </div>
                        <div class="form-group mb-4 {{$errors->has('password' ? 'is-invalid' : '')}}">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
                            {!!$errors->first('password', '<span class="invalid-feedback">:message</span>')!!}
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary px-4">Iniciar Sesion</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
                <div class="card-body text-center">
                    <div>
                        <h2>Sistema para las Elecciones 2020</h2>                                                
                        <img width="300px" src="logo/elecciones2020.jpg" alt="Elecciones 2020">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
