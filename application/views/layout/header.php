   
    <!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
            <button type="button" class="navbar-toggle pull-left animated flip">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=base_url().'home'?>"><img alt="Charisma Logo" src="<?=base_url().'img/logo20.png'?>" class="hidden-xs"/>
                <span>DISCAL</span></a>

            <!-- user dropdown starts -->
            <div class="btn-group pull-right">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> admin</span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="login.html">Logout</a></li>
                </ul>
            </div>
 
            <ul class="collapse navbar-collapse nav navbar-nav top-menu">
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown"><i class="glyphicon glyphicon-shopping-cart"></i> Productos <span
                            class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?=base_url().'producto'?>">Listar Productos</a></li>
                        <li class="divider"></li>
                        <li><a href="<?=base_url().'producto/newProduct'?>">Nuevo Producto</a></li>
                    </ul>
                </li>                
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> Clientes <span
                            class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?=base_url().'cliente'?>">Listar Clientes</a></li>                                            
                        <li class="divider"></li>
                        <li><a href="<?=base_url().'cliente/newClient'?>">Nuevo Cliente</a></li>                        
                    </ul>
                </li>
                <li><a href="#" data-toggle="dropdown"><i class="glyphicon glyphicon-briefcase"></i> Pedidos <span
                            class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?=base_url().'pedido'?>">Listar Pedidos</a></li>
                        <li class="divider"></li>
                        <li><a href="<?=base_url().'pedido/newOrder'?>">Nuevo Pedido</a></li>                        
                        <li class="divider"></li>
                        <li><a href="<?=base_url().'pedido/reportes'?>">Reportes</a></li>
                    </ul>
                </li>
                <li>
                    <form class="navbar-search pull-left">
                        <input placeholder="Search" class="search-query form-control col-md-10" name="query"
                               type="text">
                    </form>
                </li>
            </ul>

        </div>
    </div>
