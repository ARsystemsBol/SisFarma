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
      $nomcaja = $_POST['nomcaja'];
	  $usuariocaja = $_POST['usuariocaja'];        
      $sucursalcaja = $_POST['sucursalcaja'];
	  $usuario = $_SESSION['idUser'];
      $query_insert = mysqli_query($conexion, "INSERT INTO caja(nombrecaja, idusuario, idsucursal, usuarioreg ) 
                                                values ('$nomcaja', $usuariocaja, $sucursalcaja, $usuario)");
      if ($query_insert) {
        $alert = '<div class="alert alert-success" role="alert">
                Registro Completo
              </div>';
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar 
              </div>';
      }
    }
  }
 
 ?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

<!-- Page breadcrumb -->	
<!-- <nav aria-label="breadcrumb">
  	<ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	
        <li class="breadcrumb-item"><a href="registro_caja.php"><i class="fas fa-cash-register text-info"></a></i></li>
		<li class="breadcrumb-item">			
			<i class="fas fa-save text-info mr-2"></i></li>	
			<i class="fas fa-list text-info mr-2"></i></li>					
    	<li class="breadcrumb-item active" aria-current="page">Registro Caja</li>
  	</ol>
</nav> -->
<!-- REGISTRO CAJAS -->
<div class="card mb-4"> 
    <div class="card-header bg-azul text-white ">
      	<div class="d-sm-flex align-items-center justify-content-between">
        	<h1 class="h6 mb-0 text-uppercase">Registro Caja</h1> 
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
				<input type="text" class="form-control" name = "nomcaja" id="nomcaja">
			</div>  
		
			<!-- USUARIO --> 
			<div class="col-md-4">
				<label>USUARIO</label> 
					<?php
					$query_1 = mysqli_query($conexion, "SELECT idusuario, nombre FROM usuario ORDER BY idusuario ASC");
					$resultado_1 = mysqli_num_rows($query_1);
				
					?>
				<select id="usuariocaja" name="usuariocaja" class="form-control mb-4">
					<option></option>
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
															ORDER BY idsucursal ASC");
					$resultado_succaja = mysqli_num_rows($query_succaja);
				
					?>
				<select id="sucursalcaja" name="sucursalcaja" class="form-control mb-4">
					<option></option>
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
				<input type="submit" value="Guardar" class="btn btn-success btn-sm">
				<a href="registro_caja.php" class="btn btn-danger btn-sm">Cancelar</a>
			</div>
		</form> 
	</div>
</div>

<div class="card mb-2">	
	<div class="card-header bg-verde text-white ">
      	<div class="d-sm-flex align-items-center justify-content-between">
        	<h1 class="h6 mb-0 text-uppercase">Lista Cajas</h1> 
      	</div>
    </div> 
	<!-- LISTA CAJAS -->
	<div class="card-body">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12 m-auto">
				<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered" id="table">
						<thead class="thead-dark">
							<tr style="font-size:12px" class="text-center align-middle">
								<th>ID</th>
								<th>NOMBRE</th>												
								<th>USUARIO</th>
								<th>SUCURSAL</th>	
								<th>ESTADO</th>
								<th>ACCIONES</th>
							</tr>
						</thead>
						<tbody>
							<?php
							include "../conexion.php";

							$query = mysqli_query($conexion, "SELECT c.id AS idcaja, c.nombrecaja, c.estado, u.nombre AS nomusuario, s.nombre AS dessucursal, ec.descripcion  
														     	FROM caja c 
																INNER JOIN usuario u ON c.idusuario = u.idusuario
																INNER JOIN sucursal s ON c.idsucursal = s.idsucursal
																INNER JOIN estado_caja ec ON c.estado = ec.id
																ORDER BY idcaja ASC");
							$result = mysqli_num_rows($query);
							if ($result > 0) {
								while ($data = mysqli_fetch_assoc($query)) { ?>
									<tr>
										<td class="text-center"><?php echo $data['idcaja']; ?></td>
										<td><?php echo $data['nombrecaja']; ?></td>
										<td><?php echo $data['nomusuario']; ?></td>
										<td><?php echo $data['dessucursal']; ?></td>
										<td><?php echo $data['descripcion']; ?></td>
										<?php if ($data['estado'] == 2) { ?>	
										<td class="text-center">											
											<a href="editar_caja.php?id=<?php echo $data['idcaja']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>
											<?php if ($data['idcaja'] != 1) { ?>	
											<form action="eliminar_caja.php?id=<?php echo $data['idcaja']; ?>" method="post" class="confirmar d-inline">
												<button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
											</form>
											<?php } ?>		
										</td>
										<?php } ?>											
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
				</div>
			</div>	
		</div>
	</div>
</div>	




</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<?php include_once "includes/footer.php"; ?>