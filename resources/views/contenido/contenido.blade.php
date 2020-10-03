{{-- extends -> hereda de la plantilla 'principal.blade.php' --}}
{{-- section -> crea una seccion --}}

@extends('principal')
@section('contenido')    

    @if(Auth::check())
        <template v-if="menu==1">
            <tabla-bootstrap></tabla-bootstrap>
        </template>                       
    @endif    
    
@endsection