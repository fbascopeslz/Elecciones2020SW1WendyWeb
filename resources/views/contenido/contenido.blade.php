{{-- extends -> hereda de la plantilla 'principal.blade.php' --}}
{{-- section -> crea una seccion --}}

@extends('principal')
@section('contenido')    

    @if(Auth::check())
        <template v-if="menu==1">
            <acta-votos></acta-votos>
        </template>
        
        <template v-if="menu==2">
            <reportes></reportes>
        </template>  

        <template v-if="menu==3">
            <reportes></reportes>
        </template>  
    @endif    
    
@endsection