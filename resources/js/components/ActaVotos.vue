<template>
    <main class="main">        
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Actas de Votos                   
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="Usuario.login">Usuario</option>                                    
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarActasVotos(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarActasVotos(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>                                
                                <th>Cantidad Votos Total</th>
                                <th>Votos Nulos</th>
                                <th>Votos Blancos</th>                                                               
                                <th>Imagen</th>
                                <th>Hora</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="actaVotos in arrayActaVotos" :key="actaVotos.id">                                
                                <td v-text="actaVotos.cantidadVotosTotal"></td>
                                <td v-text="actaVotos.votosNulos"></td>
                                <td v-text="actaVotos.votosBlancos"></td>                                                            
                                <td><img class="preview" :src="actaVotos.imagen"></td>                                                                
                                <td v-text="actaVotos.hora"></td>
                                <td v-text="actaVotos.fecha"></td>
                            </tr>
                           
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                            </li>

                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                            </li>       

                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

    </main>
</template>

<script>    
    import Vue from 'vue';
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    // Init plugin
    Vue.use(Loading);



    export default {
        
        data() {
            return {                
                arrayActaVotos: [],
               
                tipoAccion: 0,    

                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset: 3,

                //Busqueda
                criterio: 'Usuario.login',
                buscar: '',

                //opciones para Vue loading overlay        
                optionsLoadingOverlay : {                        
                    canCancel: false,
                    color: '#007BFF',
                    height:	128,
                    width: 128
                },
            }
        },

        //propiedades computadas
        computed: {
            isActived: function() {
                return this.pagination.current_page;
            },

            //Calcula los elementos de la paginacion
            pagesNumber: function() {
                if (!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            }
        },

        methods: {
            listarActasVotos(page, buscar, criterio) {
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;

                var url = 'actaVotos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayActaVotos = respuesta.actaVotos.data;
                        me.pagination = respuesta.pagination;

                        loader.hide();
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);

                        loader.hide();
                    })
                    .then(function () {
                        // always executed
                    });
            },

            cambiarPagina(page, buscar, criterio) {
                let me = this;

                //Actualizar la pagina actual
                me.pagination.current_page = page;

                //Envia la peticion para visualizar la data de esa pagina
                me.listarActasVotos(page, buscar, criterio);
            },

        },
       
        mounted() {
            //console.log('Component mounted.')
            this.listarActasVotos(1, this.buscar, this.criterio);
        }
    }
</script>

<style>

    img.preview {
        width: 150px;
        background-color: white;
        border: 1px solid #DDD;
        padding: 5px;
    }

</style>