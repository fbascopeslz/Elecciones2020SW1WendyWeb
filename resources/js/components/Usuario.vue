<template>
    <main class="main">       
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Usuarios
                    <button type="button" @click="abrirModal('usuario', 'registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombres">Nombre</option>
                                    <option value="ci">CI</option>
                                    <option value="login">Login</option>                                   
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarUsuarios(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarUsuarios(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Login</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>CI</th>
                                <th>Telefono</th>
                                <th>Correo</th>
                                <th>Rol</th>                                                          
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="usuario in arrayUsuarios" :key="usuario.id">
                                <td>
                                    <button type="button" @click="abrirModal('usuario', 'actualizar', usuario)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    
                                    <template v-if="usuario.estado == 1">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarUsuario(usuario.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-success btn-sm" @click="activarUsuario(usuario.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>

                                </td>
                                <td v-text="usuario.login"></td>
                                <td v-text="usuario.nombres"></td>
                                <td v-text="usuario.apellidos"></td>
                                <td v-text="usuario.ci"></td>
                                <td v-text="usuario.telefono"></td>
                                <td v-text="usuario.correo"></td>
                                <td v-text="usuario.rol"></td>                                                                                                                                                  
                                <td>
                                    <div v-if="usuario.estado == 1">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>
                                </td>
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

        <!--Inicio del modal agregar/actualizar-->
        <div class="modal" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none; overflow-y: scroll;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                
                    <div class="modal-body">

                        <form method="post" enctype="multipart/form-data" class="form-horizontal">                        
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Login</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="login" class="form-control" placeholder="Login">
                                    <span class="help-block">(*) Ingrese el Login del Usuario</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Password</label>
                                <div class="col-md-9">
                                    <input type="password" v-model="password" class="form-control" placeholder="Password de acceso">                                    
                                    <span class="help-block">(*) Ingrese el Password del Usuario</span>
                                </div>                                
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombres</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombres" class="form-control" placeholder="Nombres">
                                    <span class="help-block">(*) Ingrese los Nombres del Usuario</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Apellidos</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="apellidos" class="form-control" placeholder="Apellidos">
                                    <span class="help-block">(*) Ingrese los Apellidos del Usuario</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">CI</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="ci" class="form-control" placeholder="CI">
                                    <span class="help-block">(*) Ingrese el CI del Usuario</span>
                                </div>
                            </div>                                                                                  
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono" @keypress="isNumber($event)" class="form-control" placeholder="Telefono">                                    
                                </div>
                            </div>      
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Correo</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="correo" class="form-control" placeholder="Correo">                                    
                                </div>
                            </div>                                                        
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Rol</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="idRol">
                                        <option value="0">Seleccione un rol</option>
                                        <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                    </select>
                                    <span class="help-block">(*) Seleccione el Rol del Usuario</span>
                                </div>                                
                            </div>                            
                                                    
                            <div v-show="errorUsuario == 1" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjUsuario" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>                                         
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarUsuario()">Guardar</button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarUsuario()">Actualizar</button>
                    </div>                             

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    
    </main>
</template>

<script>
    //SweetAlert2
    import Swal from 'sweetalert2';

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
                idUsuario: 0,
                login: '',
                password: '',
                nombres: '',
                apellidos: '',
                ci: '',
                telefono: 0,
                correo: '',
                idRol: 0,

                arrayRol: [],

                arrayUsuarios: [],

                modal: 0,
                tituloModal: '',

                tipoAccion: 0,

                //variable para los errores
                //bandera para ver si encontro error
                errorUsuario: 0,
                //Array con los errores encontrados
                errorMostrarMsjUsuario: [],

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
                criterio: 'nombres',
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
            //Validar que el input solo acepte numeros y punto
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
            
            
            listarUsuarios(page, buscar, criterio) {
                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;

                var url = 'usuario?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        console.log(response);

                        var respuesta = response.data;
                        me.arrayUsuarios = respuesta.usuarios.data;
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

            selectRol() {
                let me = this;

                var url = 'rol/selectRol';
                
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        //console.log(response);

                        var respuesta = response.data;
                        me.arrayRol = respuesta.roles;                        
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
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
                me.listarUsuarios(page, buscar, criterio);
            },

            registrarUsuario() {                
                //Verificar las validaciones
                if (this.validarUsuario()) {
                    return;
                }

                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;                                       

                axios.post(
                    'usuario/registrar',
                    {
                        'login': this.login,
                        'password': this.password,
                        'nombres': this.nombres,
                        'apellidos': this.apellidos,
                        'ci': this.ci,
                        'telefono': this.telefono,
                        'correo': this.correo,                
                        'idRol': this.idRol
                    }                    
                ).then(function (response) {
                    loader.hide();
                    
                    me.cerrarModal();
                    me.listarUsuarios(1, '', 'nombres');
                    
                    console.log(response);

                }).catch(function (error) {
                    console.log(error);

                    loader.hide();
                });
            },

            actualizarUsuario() {

                //Verificar las validaciones
                if (this.validarUsuario()) {
                    return;
                }

                let loader = this.$loading.show(this.optionsLoadingOverlay);

                let me = this;
            
                axios.post(
                    'usuario/actualizar',
                    {
                        'id': this.idUsuario,
                        'login': this.login,
                        'password': this.password,
                        'nombres': this.nombres,
                        'apellidos': this.apellidos,
                        'ci': this.ci,
                        'telefono': this.telefono,
                        'correo': this.correo,                
                        'idRol': this.idRol                        
                    }                                    
                ).then(function (response) {
                    loader.hide();

                    me.cerrarModal();
                    me.listarUsuarios(1, '', 'nombres');
                    
                    console.log(response);

                }).catch(function (error) {
                    console.log(error);

                    loader.hide();
                });
                
            },

            desactivarUsuario(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de desactivar a este Usuario?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        let loader = this.$loading.show(this.optionsLoadingOverlay);

                        let me = this;

                        axios.put(
                            'usuario/desactivar',
                            {
                                'id': id
                            }                                                                   
                        ).then(function (response) {   
                            loader.hide();                      
                            
                            me.listarUsuarios(1, '', 'nombres');

                            swalWithBootstrapButtons.fire(
                                'Desactivado!',
                                'El Usuario ha sido desactivado con exito.',
                                'success'
                            );

                            console.log(response);

                        }).catch(function (error) {
                            console.log(error);

                            loader.hide();
                        });
                                                
                    }
                });

            },

            activarUsuario(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Estas seguro de activar a este Usuario?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        let loader = this.$loading.show(this.optionsLoadingOverlay);

                        let me = this;

                        axios.put(
                            'usuario/activar',
                            {
                                'id': id
                            }                                                                   
                        ).then(function (response) {
                            loader.hide();

                            me.listarUsuarios(1, '', 'nombres');
                            
                            swalWithBootstrapButtons.fire(
                                'Activado!',
                                'El Usuario ha sido activado con exito.',
                                'success'
                            );

                            console.log(response);

                        }).catch(function (error) {
                            console.log(error);

                            loader.hide();
                        });
                                                
                    }
                });

            },

            cerrarModal() {
                this.modal = 0;
                this.tituloModal = '';
                                
                this.login = '';
                this.password = '';
                this.nombres = '';
                this.apellidos = '';
                this.ci = '';
                this.telefono = 0;
                this.correo = '';
                this.idRol = 0;
                               
                //limpiar las validaciones
                this.errorUsuario = 0;
                this.errorMostrarMsjUsuario = [];
            },

            abrirModal(modelo, accion, data = []) {
                
                this.selectRol();

                switch (modelo) {
                    case "usuario":
                        switch (accion) {
                            case "registrar":
                                this.modal = 1;
                                this.tituloModal = "Registrar Usuario";
                                this.tipoAccion = 1;

                                this.idUsuario = 0;
                                this.login = '';
                                this.password = '';
                                this.nombres = '';
                                this.apellidos = '';
                                this.ci = '';
                                this.telefono = 0;
                                this.correo = '';
                                this.idRol = 0;                                                                                      
                                break;

                            case "actualizar":
                                //console.log(data);                              
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Usuario';
                                this.tipoAccion = 2;

                                this.idUsuario = data['id'];                                
                                this.login = data['login'];
                                this.password = data['password'];
                                this.nombres = data['nombres'];
                                this.apellidos = data['apellidos'];
                                this.ci = data['ci'];
                                this.telefono = data['telefono'];
                                this.correo = data['correo'];                                
                                this.idRol = data['idRol'];
                                break;
                        
                            default:
                                break;
                        }
                        break;
                
                    default:
                        break;
                }
            },
                    
            validarUsuario() {
                this.errorUsuario = 0;                
                this.errorMostrarMsjUsuario = [];

                if (!this.login) {
                    this.errorMostrarMsjUsuario.push("El campo Login no puede estar vacio.");
                }

                if (!this.password) {
                    this.errorMostrarMsjUsuario.push("El campo Password no puede estar vacio.");
                }

                if (!this.nombres) {
                    this.errorMostrarMsjUsuario.push("El campo Nombres no puede estar vacio.");
                }

                if (!this.apellidos) {
                    this.errorMostrarMsjUsuario.push("El campo Apellidos no puede estar vacio.");
                }
                
                if (!this.ci) {
                    this.errorMostrarMsjUsuario.push("El campo CI no puede estar vacio.");
                }

                if (this.telefono == 0) {
                    this.errorMostrarMsjUsuario.push("El campo Telefono no puede estar vacio.");
                }
                
                if (!this.correo) {
                    this.errorMostrarMsjUsuario.push("El campo Correo no puede estar vacio.");
                }

                if (this.idRol == 0) {
                    this.errorMostrarMsjUsuario.push("El campo Rol no puede estar vacio.");
                }
                
                if (this.errorMostrarMsjUsuario.length) {
                    this.errorUsuario = 1;
                }

                return this.errorUsuario;
            },                        

        },
       
        mounted() {
            //console.log('Component mounted.')
            this.listarUsuarios(1, this.buscar, this.criterio);                        
        }
    }
    
</script>

<style>
    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3C29297A !important;
    }

    .div-error {
        display: flex;
        justify-content: center;        
    }
    .text-error {
        color: red !important;
        font-weight: bold;
    }

</style>