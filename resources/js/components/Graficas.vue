<template>
<main class="main">   
    <div class="row">
        
        <div class="col-md-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title"><h2 style="text-align:center;">RESULTADOS EN BOCA DE URNA</h2></h3>
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h4>Total de votos contabilizados: </h4>
                            <h3 v-text="totalVotos"></h3>
                            <p>Datos actualizados a {{hora}} del {{fecha}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer"></a>
                    </div>                       
                </div>
            </div>
        </div>
          
        <div class="col-md-12">               
            <div class="card card-chart">
                <div class="card-header">
                    <h4>Grafica de porcentajes %</h4>
                </div>
                <div class="card-content">
                    <div class="ct-chart">
                        <canvas id="pieChart" style="height:250px"></canvas>
                    </div>
                </div>
                <div class="card-footer">
                    <p>Partido vs Porcentaje de Votos</p>
                </div>
            </div>                                 
        </div>
     
        <div class="col-md-12">
            <div class="card card-chart">
                <div class="card-header">
                    <h4>Grafica de cantidades</h4>
                </div>
                <div class="card-content">
                    <div class="ct-chart">
                        <canvas id="barChart" style="height:250px"></canvas>
                    </div>
                </div>
                <div class="card-footer">
                    <p>Partido vs Cantidad de Votos</p>
                </div>
            </div>       
        </div> 

    </div>    

</main>
</template>
<script>
    import Chart from 'chart.js';

    import Vue from 'vue';
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    // Init plugin
    Vue.use(Loading);


    export default {
        data (){
            return {                        
                
                arrayResultados: [],
                totalVotos: 0,

                arraySiglasPartidos: [],
                arrayPorcentajeVotos: [],
                arrayVotosTotales: [],
                arrayColoresPartidos: [],

                hora: '',
                fecha: '',                                                                       

                //opciones para Vue loading overlay        
                optionsLoadingOverlay : {                        
                    canCancel: false,
                    color: '#007BFF',
                    height:	128,
                    width: 128
                },
            }
        },

        methods : {
            listarResultadosNacionalesGraficas() {
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;

                var url = 'listarResultadosNacionalesGraficas';                              
                
                axios.get(url).then(function (response) {
                    console.log(response);
                    var respuesta = response.data;                     

                    console.log(me.respuesta);

                    //Sacando los porcentajes
                    //var totalVotos = 0;        
                    for (var i=0; i < respuesta.length; i++) 
                    { 
                        me.totalVotos += parseInt(respuesta[i].VotosTotales);                       
                    }

                    for (var i=0; i < respuesta.length; i++) 
                    {                         
                        me.arrayResultados.push(
                            {
                                'siglaPartido': respuesta[i].SiglaPartido,
                                'colorHex': respuesta[i].ColorHex,
                                'porcentaje': Math.round((respuesta[i].VotosTotales * 100) / me.totalVotos),
                                'votos': respuesta[i].VotosTotales,
                            }
                        );
                    }  

                    //Separar Arrays para las graficas
                    me.arrayResultados.map(function(x) {
                        me.arraySiglasPartidos.push(x.siglaPartido);
                        me.arrayPorcentajeVotos.push(x.porcentaje);
                        me.arrayVotosTotales.push(x.votos);
                        me.arrayColoresPartidos.push(x.colorHex);
                    });

                    console.log(me.arraySiglasPartidos);
                    console.log(me.arrayVotosTotales);
                    console.log(me.arrayColoresPartidos);
                    console.log(me.arrayPorcentajeVotos);
                    
                    //Cargar las graficas
                    me.cargarGraficaPie();
                    me.cargarGraficaBarras();

                    loader.hide();
                })
                .catch(function (error) {
                    console.log(error);

                    loader.hide();
                });
            },

            cargarGraficaPie() {                
                this.ctx = document.getElementById('pieChart').getContext('2d');
                this.myDoughnutChart = new Chart(this.ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: this.arrayPorcentajeVotos,
                            backgroundColor: this.arrayColoresPartidos,
                        }],                        
                        labels: this.arraySiglasPartidos
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Productos Mas Vendidos'
                        },
                        animation: {
                            animationScale: true,
                            animationRotate: true
                        },
                        
                    }                   
                });
            },

            cargarGraficaBarras() {                                
                var ctx = document.getElementById('barChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: this.arraySiglasPartidos,
                        datasets: [{
                            label: 'Numero de Votos',
                            data: this.arrayVotosTotales,
                            backgroundColor: this.arrayColoresPartidos,
                            borderColor: this.arrayColoresPartidos,
                            borderWidth: 5
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            },

        },

        mounted() {            
            this.listarResultadosNacionalesGraficas();

            //FECHA Y HORA ACTUAL
            var currentdate = new Date();
            //Hora Actual
            this.fecha = currentdate.getDate() + "/" 
                + (currentdate.getMonth()+1) + "/" 
                + currentdate.getFullYear();
            //Fecha Actual            
            this.hora = currentdate.getHours() + ":" 
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();      
        }
    }
</script>
