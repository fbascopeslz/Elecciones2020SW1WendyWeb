<template>
     <main class="main">        
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Resultados en Boca de Urna                   
                </div>
                
                <div class="card-body">
                    <div class="card">
                        <div class="card-header">
                            Elija el criterio para mostrar los resultados
                        </div>    
                        <div class="card-body">
                            <select class="form-control" @change="onChangeSelect($event)" v-model="opcionMostrar">
                                <option value="1">Nivel Nacional</option>
                                <option value="2">Nivel Departamental</option>
                                <option value="3">Nivel Recinto</option>
                            </select>

                            <template v-if="opcionMostrar == 1">

                            </template>
                            
                            <template v-if="opcionMostrar == 2">
                                <div>
                                    Seleccione el Departamento: 
                                    <select class="form-control" @change="onChangeSelectDepartamento($event)" v-model="opcionIdDepartamento">
                                        <option value="">Seleccione</option>
                                        <option v-for="dep in arrayDepartamentos" :key="dep.id" :value="dep.id" v-text="dep.nombre"></option>
                                    </select>
                                </div>                                                            
                            </template>
                            
                            <template v-if="opcionMostrar == 3">
                                <div>
                                    Seleccione el Recinto: 
                                    <select class="form-control" @change="onChangeSelectRecinto($event)" v-model="opcionIdRecinto">
                                        <option value="">Seleccione</option>
                                        <option v-for="rec in arrayRecintos" :key="rec.id" :value="rec.id" v-text="rec.nombre"></option>
                                    </select>
                                </div>
                            </template>

                        </div>
                    </div>

                    <bootstrap-table :data="arrayResultados" :options="myOptions" :columns="columns" />
                                        
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

    </main>
</template>

<script>
    import BootstrapTable from 'bootstrap-table/dist/bootstrap-table-vue.min.js'

    import Vue from 'vue';
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    // Init plugin
    Vue.use(Loading);


    export default {
        components: {
            'bootstrap-table': BootstrapTable
        },

        data () {
            return {                
                myOptions: {
                    search: true, 
                    pagination: true,
                    showColumns: true,
                    showPrint: true,
                    showExport: true,
                    filterControl: true,
                    clickToSelect: true,
                                                         
                },
                
                columns: [                    
                    {
                        title: 'Nombre del Partido',
                        field: 'NombrePartido'
                    },
                    {                        
                        title: 'Sigla del Partido',
                        field: 'SiglaPartido',
                    },
                    {                    
                        title: 'Votos Totales',
                        field: 'VotosTotales'
                    },
                    
                ],                


                arrayResultados: [],

                opcionMostrar: "1",

                arrayDepartamentos: [],
                opcionIdDepartamento: 1,

                arrayRecintos: [],
                opcionIdRecinto: 1,

                              
                //opciones para Vue loading overlay        
                optionsLoadingOverlay : {                        
                    canCancel: false,
                    color: '#007BFF',
                    height:	128,
                    width: 128
                },                    

            }
        },
        
        methods: {
            listarResultadosNacionales(page, buscar, criterio) {
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;

                var url = 'listarResultadosNacionales';
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayResultados = respuesta;                        

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
            
            listarResultadosDepartamentales(idDepartamento) {
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;

                var url = 'listarResultadosDepartamentales?idDepartamento=' + idDepartamento;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayResultados = respuesta;                        

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

            listarResultadosRecintos(idRecinto) {
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;

                var url = 'listarResultadosRecintos?idRecinto=' + idRecinto;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayResultados = respuesta;                        

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
            

            getDepartamentos() {
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;

                var url = 'getDepartamentos';
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        console.log(response);

                        var respuesta = response.data;
                        me.arrayDepartamentos = respuesta;                        

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

            getRecintos() {
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;

                var url = 'getRecintos';
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        console.log(response);

                        var respuesta = response.data;
                        me.arrayRecintos = respuesta;                        

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


            onChangeSelect(event) {                
                console.log(this.opcionMostrar);
                switch (this.opcionMostrar) {
                    case "1":
                        this.listarResultadosNacionales();
                        break;
                    
                    case "2":
                        this.getDepartamentos();
                        break;

                    case "3":
                        this.getRecintos();
                        break;
                
                    default:
                        break;
                }
            },

            onChangeSelectDepartamento(event) {                
                console.log(this.opcionIdDepartamento);
                this.listarResultadosDepartamentales(this.opcionIdDepartamento);
            },

            onChangeSelectRecinto(event) {                
                console.log(this.opcionIdRecinto);
                this.listarResultadosRecintos(this.opcionIdRecinto);
            },

        },
       
        mounted() {
            //console.log('Component mounted.')
            this.listarResultadosNacionales();
        }
    }
</script>