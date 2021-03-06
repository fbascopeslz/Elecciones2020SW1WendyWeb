<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
    
        <li @click="menu=1" class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-vote-yea"></i>
                <p>Acta de Votos</p>
            </a>
        </li>

        <li @click="menu=2" class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-poll-h"></i>
                <p>Resultados</p>
            </a>
        </li>

        <li @click="menu=3" class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-bar"></i>
                <p>Graficas</p>
            </a>
        </li>

        <li @click="menu=4" class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>Usuarios</p>
            </a>
        </li> 

    </ul>
</nav>
<!-- /.sidebar-menu -->