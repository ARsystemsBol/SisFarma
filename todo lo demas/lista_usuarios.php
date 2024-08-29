<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

	<!-- Page breadcrumb -->	
<!-- 	<nav aria-label="breadcrumb">
  		<ol class="breadcrumb bg-azul">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-white"></a></i></li>	        	
        	<li class="breadcrumb-item"><a href="lista_usuarios.php"><i class="fa fa-users text-white"></a></i></li>			
    		<li class="breadcrumb-item active text-gray-600" aria-current="page">Lista de Usuarios</li>
  		</ol>
  	</nav> -->
  	<!-- End Page breadcrumb -->

	<!-- Page Heading -->
	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">Lista de Usuarios</h1>
        		<?php if ($_SESSION['rol'] == 1) { ?>
				<a href="registro_usuario.php" class="btn btn-success btn-sm"><i class="fas fa-plus fa-xs mr-2"></i>Nuevo</a>
				<?php } ?>
      		</div>
    	</div>    
    <div class="card-body">
		<div class="row">
			<div class="col-lg-12">
				<div class="table-responsive">
					<table class="table table-sm table-striped table-bordered" id="table">
						<thead class="thead-dark">
							<tr class="text-center aligin-middle" style="font-size:12px;">
								<th>ID</th>
								<th>NOMBRE</th>							
								<th>USUARIO</th>
								<th>ROL</th>
								<th>SUCURSAL</th>
								<?php if ($_SESSION['rol'] == 1) { ?>
								<th>ACCIONES</th>
								<?php }?>
							</tr>
						</thead>
						<tbody>
							<?php
							include "../conexion.php";

							$query = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.usuario, r.rol, s.nombre As dessucursal
															FROM usuario u 
															INNER JOIN rol r ON u.rol = r.idrol
															INNER JOIN sucursal s ON u.idsucursal = s.idsucursal");
							$result = mysqli_num_rows($query);
							if ($result > 0) {
								while ($data = mysqli_fetch_assoc($query)) { ?>
									<tr>
										<td  style="font-size:14px;" class= text-center><?php echo $data['idusuario']; ?></td>
										<td style="font-size:14px;" ><?php echo $data['nombre']; ?></td>
										<td style="font-size:14px;" ><?php echo $data['usuario']; ?></td>
										<td style="font-size:14px;" ><?php echo $data['rol']; ?></td>
										<td style="font-size:14px;" ><?php echo $data['dessucursal']; ?></td>
										<?php if ($_SESSION['rol'] == 1) { ?>
										<td class="text-center">
											<a href="editar_usuario.php?id=<?php echo $data['idusuario']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit fa-sm'></i></a>
											<?php if ($data['idusuario'] != 1) { ?>
											<form action="eliminar_usuario.php?id=<?php echo $data['idusuario']; ?>" method="post" class="confirmar d-inline">
												<button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt fa-sm'></i> </button>
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