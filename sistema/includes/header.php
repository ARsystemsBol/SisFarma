<?php
session_start();

if (empty($_SESSION['active'])) {
	header('location: ../');

} 

include "includes/functions.php";
include "../conexion.php";
// datos Empresa
$dni = '';
$nombre_empresa = '';
$razonSocial = '';
$emailEmpresa = '';
$telEmpresa = '';
$dirEmpresa = '';
$iva = '';

$query_empresa = mysqli_query($conexion, "SELECT * FROM configuracion");
$row_empresa = mysqli_num_rows($query_empresa);
if ($row_empresa > 0) {
	if ($infoEmpresa = mysqli_fetch_assoc($query_empresa)) {
		$nit = $infoEmpresa['nit'];
		$nombre_empresa = $infoEmpresa['nombre'];
		$razonSocial = $infoEmpresa['razon_social'];
		$telEmpresa = $infoEmpresa['telefono'];
		$emailEmpresa = $infoEmpresa['email'];
		$dirEmpresa = $infoEmpresa['direccion'];
		$iva = $infoEmpresa['iva'];
	}
}
$query_data = mysqli_query($conexion, "CALL data();");
$result_data = mysqli_num_rows($query_data);
if ($result_data > 0) {
	$data = mysqli_fetch_assoc($query_data);
}


							
$desestadocaja = $_SESSION['estadocaja'];
if ($desestadocaja != 'ABIERTA'){
  $classestadocaja = 'text-danger';
}
else{
  $classestadocaja = 'text-success';
};



?>
<!DOCTYPE html>
<html lang="es">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title><?php echo $razonSocial; ?></title>

	<!-- Custom styles for this template-->
	<link rel="shortcut icon" href="img/farmacia2_favicon.png">
	<!-- ICONOS BOOSTRAP -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
	<link href="css/sb-admin-2.min.css" rel="stylesheet">
	<link href="css/estilologin.css" rel="stylesheet">	
	<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
	
	<!-- FONTAWESOME -->
	
	<script src="https://kit.fontawesome.com/1ea8486269.js" crossorigin="anonymous"></script>
</head>

<body id="page-top">
	<?php
	include "../conexion.php";
	$query_data = mysqli_query($conexion, "CALL data();");
	$result_data = mysqli_num_rows($query_data);
	if ($result_data > 0) {
		$data = mysqli_fetch_assoc($query_data);
	}

	?>
	<!-- Page Wrapper -->
	<div id="wrapper">

		<?php include_once "includes/menu.php"; ?>
		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">
			

			<!-- Main Content -->
			<div id="content">
				<!-- Topbar -->
				<nav class="navbar navbar-expand navbar-light bg-verde text-white topbar fixed-top mb-5 shadow">
					<!-- Sidebar Toggle (Topbar) -->
					<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
						<div class="sidebar-brand-icon ml-3 ">
							<img src="img/farmacia2.jpg" width="50px" height="50px" style="border-radius:50px" >
						</div>
						
					</a>
					<div class="sidebar-brand-text mx-4">SIS FARMA</div>
					<div class="topbar-divider d-none d-sm-block pr-2 "></div>
					<button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3 text-orange">
						<i class="fa fa-bars"></i>
					</button>
					<div class="input-group">
						<h6 ><?php echo $razonSocial; ?></h6>						
					</div>

					<!-- Topbar Navbar -->
					<ul class="navbar-nav ml-auto">
					<div class="topbar-divider d-none d-sm-block"></div>

						<!-- Nav Item - Cajas -->
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline small text-white"><i class="fas fa-cash-register  mr-2 text-warning"></i><?php echo $_SESSION['caja']; ?></span>
								<!-- <span class="mr-2 d-none d-lg-inline small <?php echo $classestadocaja?>"><?php echo $_SESSION['estadocaja']; ?></span> -->
							</a>							
						</li>

						<div class="topbar-divider d-none d-sm-block"></div>

						<!-- Nav Item - Sucursal -->
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<!-- <span class="mr-2 d-none d-lg-inline small text-white"><?php echo $_SESSION['idsuc']; ?></span>		 -->						
								<span class="mr-2 d-none d-lg-inline small text-white"><i class="fas fa-building mr-2 text-orange"></i> <?php echo $_SESSION['sucursal']; ?></span>
							</a>							
						</li>

						<div class="topbar-divider d-none d-sm-block"></div>

						<!-- Nav Item - usuario -->
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline small text-white"><i class="fas fa-user mr-2 text-info"></i><?php echo $_SESSION['nombre']; ?></span>
								<div class="topbar-divider d-none d-sm-block"></div>
								<span class="mr-2 d-none d-lg-inline small text-white"><?php echo $_SESSION['rol_name']; ?></span> 

							</a>
							<!-- Dropdown - User Information -->
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
								<a class="dropdown-item" href="#">
									<i class="fas fa-user fa-sm fa-fw mr-2 text-azul"></i>
									<?php echo $_SESSION['email']; ?>
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="salir.php">
									<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-rojo"></i>
									Salir
								</a>
							</div>
						</li>

					</ul>

				</nav>
				<!-- End of Topbar -->