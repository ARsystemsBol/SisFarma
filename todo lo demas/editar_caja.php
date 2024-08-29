<?php include_once "includes/header.php"; 
 include "../conexion.php";
 if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nomcaja']) 
	|| ($_POST['usuariocaja'] < 0)
	|| ($_POST['sucursalcaja'] < 0))  {
      $alert = '<div class="alert alert-danger" role="alert">
                Todos los campos son obligatorios
              </div>';    
      
    } else { 
	  $idcaja = $_GET['id'];
      $nomcaja = $_POST['nomcaja'];	
	  $usuariocaja = $_POST['usuariocaja'];        
      $sucursalcaja = $_POST['sucursalcaja'];
	  
	  $sql_update = mysqli_query($conexion, "UPDATE caja 
	  										SET nombrecaja = '$nomcaja',
											  idusuario = $usuariocaja,
											  idsucursal = $sucursalcaja
                                            WHERE id = $idcaja
											AND estado != 1");
       if ($sql_update) {
		$alert = '<div class="alert alert-success" role="alert"> Caja Actualizada </div>';
	  } else {
		$alert = '<div class="alert alert-danger" role="alert"> No se puede actualizar una caja abierta  </div>';
	  }
	}
  }

  // Mostrar Datos

if (empty($_REQUEST['id'])) {
	header("Location: registro_caja.php");

  }
  $idcaja = $_REQUEST['id'];
  $sql = mysqli_query($conexion, "SELECT c.id AS idcaja, c.nombrecaja, c.idusuario, c.idsucursal, c.estado, u.nombre AS nomusuario, 
								s.nombre AS dessucursal
								FROM caja c 
								INNER JOIN usuario u ON c.idusuario = u.idusuario
								INNER JOIN sucursal s ON c.idsucursal = s.idsucursal								
								WHERE id = $idcaja");

  $result_sql = mysqli_num_rows($sql);
  if ($result_sql == 0) {
		header("Location: registro_caja.php");
  	} else {		
		while ($data = mysqli_fetch_array($sql)) {	
			$nomcajaa = $data['nombrecaja'];
			$iduscaja = $data['idusuario'];
			$nomuscaja = $data['nomusuario'];
			$dessuccaja = $data['dessucursal'];
			$idsuccaja = $data['idsucursal'];	
		  } 
	}
  
 ?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

<!-- Page breadcrumb -->	
<nav aria-label="breadcrumb">
  	<ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	
        <li class="breadcrumb-item"><a href="registro_caja.php"><i class="fas fa-cash-register text-info"></a></i></li>
		<li class="breadcrumb-item">			
			<i class="fas fa-save text-info mr-2"></i></li>	
			<i class="fas fa-list text-info mr-2"></i></li>					
    	<li class="breadcrumb-item active" aria-current="page">Registro Caja</li>
  	</ol>
</nav>
<!-- REGISTRO CAJAS -->
<div class="card mb-4"> 
    <div class="card-header bg-gradient-dark text-white ">
      	<div class="d-sm-flex align-items-center justify-content-between mb-1">
        	<h1 class="h6 mb-0 text-uppercase">Editar Caja</h1> 
      	</div>
    </div>    
    <div class="card-body">
		<div class ="col-8 m-auto">
        	<?php echo isset($alert) ? $alert : ''; ?>
      	</div>  

		<form class="row col-10 m-auto p-3" action="" method="post" autocomplete="off">          
		
			<!-- NOMBRE CAJA--> 
			<div class="col-md-4 mb-2">
				<label for="nomcaja" class="form-label ">NOMBRE CAJA <span class="text-danger"> *</span> </label>
				<input value="<?php echo $nomcajaa; ?>" type="text" class="form-control" name = "nomcaja" id="nomcaja">
			</div>  
		
			<!-- USUARIO --> 
			<div class="col-md-4">
				<label>USUARIO</label> 
					<?php
					$query_1 = mysqli_query($conexion, "SELECT idusuario, nombre FROM usuario ORDER BY idusuario ASC");
					$resultado_1 = mysqli_num_rows($query_1);
				
					?>
				<select id="usuariocaja" name="usuariocaja" class="form-control mb-4">
					<option value="<?php echo $iduscaja; ?>"><?php echo $nomuscaja; ?></option>
					<?php
					if ($resultado_1 > 0) {
						while ($uscaja = mysqli_fetch_array($query_1)) {
						// code...
					?>
						
						<option value="<?php echo $uscaja['idusuario']; ?>"><?php echo $uscaja['nombre']; ?></option>
						
					<?php
					
						}
					}
					?>
				</select>
			
			</div>

			<!-- SUCURSAL --> 
			<div class="col-md-4">
				<label>SUCURSAL</label> 
				<?php
					$query_succaja = mysqli_query($conexion, "SELECT idsucursal, nombre 
															FROM sucursal 
															WHERE idsucursal != 1
															ORDER BY idsucursal ASC");
					$resultado_succaja = mysqli_num_rows($query_succaja);
				
					?>
				<select id="sucursalcaja" name="sucursalcaja" class="form-control mb-4">
					<option value="<?php echo $idsuccaja; ?>"><?php echo $dessuccaja; ?></option>
					<?php
					if ($resultado_succaja > 0) {
						while ($succaja = mysqli_fetch_array($query_succaja)) {
						// code...
					?>
						
						<option value="<?php echo $succaja['idsucursal']; ?>"><?php echo $succaja['nombre']; ?></option>
					<?php
						}
					}
					?>
				</select>
			
			</div>

			<!-- BOTONES -->		
			<div class="col-12">
				<input type="submit" value="Editar" class="btn btn-primary btn-sm">
				<a href="registro_caja.php" class="btn btn-danger btn-sm"> Volver</a>
			</div>
		</form> 
	</div>
</div>

	




</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<?php include_once "includes/footer.php"; ?>