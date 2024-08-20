
<!-- Sidebar -->
<ul class="navbar-nav bg-verde sidebar sidebar-dark accordion sticky-top" id="accordionSidebar">

	<!-- Sidebar - Brand "logo farmacia" -->
<!-- 	<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
		<div class="sidebar-brand-icon">
			<img src="img/farmacia2.jpg" width="50px" height="50px" style="border-radius:50px" >
		</div>
		<div class="sidebar-brand-text mx-4">SIS FARMA</div>
	</a>
 -->
	<!-- Divider -->
	<hr class="sidebar-divider my-1">

	<!-- Nav Item - Panel de Control Collapse Menu -->
	<li class="nav-item">
		<a class="nav-link collapsed" href="index.php">
				
			<span>Panel de Control</span>
		</a>		
	</li>
	

	<!-- Divider -->
	<hr class="sidebar-divider">
	
	
	<!-- Heading -->
	<div class="sidebar-heading text-gray-500">
		GESTIÓN VENTAS
	</div>	
	<hr class="sidebar-divider">
	<!-- Nav Item - Reportes Collapse Menu -->	
	<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReport" aria-expanded="true" aria-controls="collapseReport">
			<i class="fas fa-fw fa-book mr-2 text-info"></i>
			<span>Reportes</span>			
		</a>
		<div id="collapseReport" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item" href="#"><i class="fas fa-user mr-2 text-azul"></i>Usuarios</a>
				<a class="collapse-item" href="documentos/grListProv.php" target="_blank"><i class="fas fa-fw fa-building mr-2 text-orange"></i>Proveedores</a>
				<a class="collapse-item" href="documentos/grKarMedGeneral.php" target="_blank"><i class="fas fa-fw fa-file mr-2 text-verde"></i>Kardex General</a>				
				<a class="collapse-item" href="documentos/grListMed.php" target="_blank"><i class="fas fa-fw fa-tablets mr-2 text-rojo"></i>Med.  General</a>
				<a class="collapse-item" href="report_medicamentos.php"><i class="fas fa-fw fa-capsules mr-2 ml-4 text-verde"></i>Med. Filtro</a>
			</div>
		</div>
	</li>

	<!-- Nav Item - Cajas Collapse Menu -->
	<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCajas" aria-expanded="true" aria-controls="collapseCajas">
			<i class="fas fa-cash-register mr-2 text-warning"></i>
			<span>Cajas</span>
		</a>
		<div id="collapseCajas" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item" href="a_caja.php"><i class="fas fa-key mr-2 text-verde"></i>Apertura/Cierre</a>
				<a class="collapse-item" href="registro_comprasm.php"><i class="fas fa-coins mr-2 text-warning"></i>Compras menores</a>		
				
		</div>
	</li>

	<!-- Nav Item - Ventas Collapse Menu -->
	<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
			<i class="fas fa-comment-dollar mr-2 text-orange"></i>
			<span>Ventas</span>
		</a>
		<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">	
				<a class="collapse-item" href="registro_ventas.php"><i class="fas fa-credit-card mr-2 text-verde"></i>Ventas Med</a>			
				<a class="collapse-item" href="ventas.php"><i class="fas fa-chart-pie mr-2 text-azul"></i>Ventas</a>				
			</div>
		</div>
	</li>

	<!-- Heading -->
	<?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3) ) { ?>
	<div class="sidebar-heading text-gray-500">
		GESTIÓN FARMACOS
	</div>
	<hr class="sidebar-divider">
	<!-- Nav Item - Lotes Collapse Menu -->
	<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLotes" aria-expanded="true" aria-controls="collapseLotes">
			<i class="fas fa-boxes mr-2 text-cafe"></i>
			<span>Lotes</span>
		</a>
		<div id="collapseLotes" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item" href="registro_lote.php"><i class="fa fa-box mr-2 text-cafe"></i>Ingreso Lote</a>
				<a class="collapse-item" href="lista_lote.php"><i class="fas fa-list mr-2 text-rojo"></i>Detalle Lotes</a>				
			</div>
		</div>
	</li>

	<!-- Nav Item - Stock Collapse Menu -->
	<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStock" aria-expanded="true" aria-controls="collapseStock">
			<i class="fas fa-clipboard-list mr-2 text-azul"></i>
			<span>Stock</span>
		</a>
		<div id="collapseStock" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item" href="stock.php"><i class="fas fa-list mr-2 text-rojo"></i>Stock General</a>
			</div>
		</div>
	</li>

	<!-- Nav Item - Traspasos Collapse Menu -->
	<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTransferencias" aria-expanded="true" aria-controls="collapseTransferencias">
			<i class="fas fa-sync-alt mr-2 text-info"></i>
			<span>Traspasos</span>
		</a>
		<div id="collapseTransferencias" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item" href="registro_traspaso.php"><i class="fas fa-upload mr-2 text-verde"></i>Traspaso - Devolucion</a>				
				<a class="collapse-item" href="lista_traspasos.php"><i class="fas fa-list mr-2 text-rojo"></i>Traspasos</a>
				<a class="collapse-item" href="lista_devoluciones.php"><i class="fas fa-list mr-2 text-azul"></i>Devoluciones</a>
			</div>
		</div>
	</li>

	<!-- Nav Item - Medicamentos Collapse Menu -->
	<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
			<i class="bi bi-prescription2" style="font-size: 1.2rem;"></i>
			
			<span>Medicamentos</span>
		</a>
		<div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item" href="lista_medicamentos.php"><i class="fas fa-notes-medical mr-2 text-rojo"></i>Lista Medicamentos</a>
				<a class="collapse-item" href="registro_medicamento.php"><i class="fas fa-pills mr-2 text-azul"></i>Reg. Medicamento</a>
				<a class="collapse-item" href="registro_pactivo.php"><i class="fas fa-vial mr-2 text-verde"></i>P. Activo</a>				
				<a class="collapse-item" href="registro_formafarmaceutica.php"><i class="fas fa-capsules mr-2 text-orange"></i>Forma Farmaceutica</a>
				<a class="collapse-item" href="registro_accionterapeutica.php"><i class="fas fa-stethoscope mr-2 text-info"></i>Accion Terapeutica</a>		
				<a class="collapse-item" href="registro_unidades.php"><i class="fas fa-vials mr-2 text-azul"></i>Unidades</a>				
			</div>
		</div>
	</li>
	<?php } ?>

	<!-- Heading Nro. 2 -->
	<div class="sidebar-heading text-gray-500">
		GESTIÓN DE REGISTROS
	</div>
	<hr class="sidebar-divider">
	<!-- Nav Item - Clientes Collapse Menu -->
	<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClientes" aria-expanded="true" aria-controls="collapseUtilities">
			<i class="fas fa-users mr-2 text-orange"></i>
			<span>Clientes</span>
		</a>
		<div id="collapseClientes" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item" href="registro_cliente.php"><i class="fas fa-user mr-2 text-azul"></i>Nuevo Clientes</a>
				<a class="collapse-item" href="lista_cliente.php"><i class="fas fa-list mr-2 text-rojo"></i>Clientes</a>
			</div>
		</div>
	</li>

	<!-- Nav Item - Proveedores Collapse Menu -->
	<?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3))  { ?>
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProveedor" aria-expanded="true" aria-controls="collapseUtilities">
				<i class="fas fa-building mr-2 text-celeste"></i>
				<span>Proveedor</span>
			</a>
			<div id="collapseProveedor" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					<a class="collapse-item" href="registro_proveedor.php"><i class="fas fa-vial mr-2 text-verde"></i>Nuevo Proveedor</a>
					<a class="collapse-item" href="lista_proveedor.php"><i class="fas fa-list mr-2 text-rojo"></i>Proveedores</a>
				</div>
			</div>
		</li>
	<?php } ?>

	<!-- Nav Item - Sucursales/Almacenes Collapse Menu -->
	<?php if ($_SESSION['rol'] == 1)  { ?>
		<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSucursal" aria-expanded="true" aria-controls="collapseUtilities">
			<i class="fas fa-store mr-2 text-success"></i>
			<span>Sucursales</span>
		</a>
		<div id="collapseSucursal" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item" href="registro_sucursal.php"><i class="fa fa-store mr-2 text-rojo"></i>Nueva Sucursal</a>
				<a class="collapse-item" href="lista_sucursales.php"><i class="fas fa-list mr-2 text-azul"></i>Sucursales</a>
			</div>
		</div>		
		</li>
	

		<!-- Nav Item - Asignacion de Cajas Collapse Menu -->
	
			<!-- Nav Item - Soporte Menu -->
			<li class="nav-item">
			<a class="nav-link collapsed" href="registro_caja.php">
				<i class="fas fa-cash-register mr-2 text-warning"></i>
				<span>Asignacion Cajas</span>
			</a>		
		</li>

	<!-- Nav Item - Usuarios Collapse Menu -->
	
		<!-- Nav Item - Usuarios Collapse Menu -->
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsuarios" aria-expanded="true" aria-controls="collapseUtilities">
				<i class="fas fa-user-nurse mr-2 "></i>
				<span>Usuarios</span>
			</a>
			<div id="collapseUsuarios" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					<a class="collapse-item" href="registro_usuario.php"><i class="fa fa-hospital-user mr-2 text-azul"></i>Nuevo Usuario</a>
					<a class="collapse-item" href="lista_usuarios.php"><i class="fas fa-list mr-2 text-rojo"></i>Usuarios</a>
				</div>
			</div>
		</li>
	<?php } ?>

	<!-- Heading -->
	<div class="sidebar-heading text-gray-500">
		AJUSTES
	</div>
	<hr class="sidebar-divider">
	
	<!-- Nav Item - Utilitarios Collapse Menu -->
<!-- 	<li class="nav-item">
		<?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
		<a class="nav-link collapsed"  href="#"data-toggle="collapse" data-target="#collapseUtilitarios" aria-expanded="true" aria-controls="collapseUtilitarios">
			<i class="fas fa-exclamation-circle mr-2 text-warning" ></i>
			<span>Utilitarios</span>
		</a>
		<?php } ?>
		<div id="collapseUtilitarios" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">				
			<a class="collapse-item" href="#"><i class="fa fa-upload mr-2 text-verde" ></i>Exportar</a>
				<a class="collapse-item" href="#"><i class="fa fa-download mr-2 text-rojo" ></i>Importar</a>
			</div>
		</div>
	</li> -->

	<!-- Nav Item - Configuraciones Collapse Menu -->

		<li class="nav-item">
			<a class="nav-link collapsed" href="configuracion.php">
				<i class="fas fa-cog mr-2 text-plomo"></i>
				<span>Configuraciones</span>
			</a>		
		</li>
		<!-- Nav Item - Soporte Menu -->
<!-- 	<li class="nav-item">
			<a class="nav-link collapsed" href="soporte.php">
				<i class="fas fa-exclamation-circle mr-2 text-orange"></i>
				<span>Help</span>
			</a>		
		</li> -->
	
</ul>
<!-- End of Sidebar -->