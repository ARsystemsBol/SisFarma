<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">
<!-- Page Heading -->	
	<!-- <nav aria-label="breadcrumb">
  		<ol class="breadcrumb bg-azul">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-white"></a></i></li>
			<li class="breadcrumb-item"><a href="lista_sucursales.php"><i class="fas fa-store text-white"></a></i></li>
    		<li class="breadcrumb-item active text-gray-500" aria-current="page">Sucursales</li>
  		</ol>
	</nav>	
 -->
	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">Lista Sucursales</h1> 
				<?php if ($_SESSION['rol'] == 1) { ?>	       		
				<a href="registro_sucursal.php" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i>Nueva</a>
				<?php  } ?>					
      		</div>
    	</div>    
    <div class="card-body">
	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-sm table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr style="font-size:12px" class="text-center align-middle">
							<th >ID</th>
							<th width="150px">NOMBRE</th>
							<th>DIRECCIÓN</th>
							<th>TELÉFONO</th>
							<th>CIUDAD</th>
							<th>TIPO SUCURSAL</th>
							<?php if ($_SESSION['rol'] == 1) { ?>													
							<th>ACCIONES</th>
							<?php }?>							
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT s.idsucursal, s.nombre, s.direccion, s.telefono,
														s.ciudad, s.almacen, u.nombre AS desusuario,
														ts.descripcion
														FROM sucursal s
														INNER JOIN usuario u ON s.idsucursal = u.idusuario
														INNER JOIN tiposucursal ts ON s.almacen = ts.id");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr style="font-size:14px" >
									<td class="text-center align-middle"><?php echo $data['idsucursal']; ?></td>
									<td width="150px" class="align-middle"><?php echo $data['nombre']; ?></td>
									<td class="text-rigth align-middle"><?php echo $data['direccion']; ?></td>
									<td class="text-rigthr align-middle"><?php echo $data['telefono']; ?></td>
									<td class="text-center align-middle"><?php echo $data['ciudad']; ?></td>
									<td class="text-center align-middle"><?php echo $data['descripcion']; ?></td>									
									<?php if (($_SESSION['rol'] == 1)) { ?>
									<td class="text-center">
										<a href="detalle_sucursal.php?id=<?php echo $data['idsucursal']; ?>" class="btn btn-primary btn-sm mr-1 mb-1"><i class='fas fa-eye'></i></a>
										<a href="editar_sucursal.php?id=<?php echo $data['idsucursal']; ?>" class="btn btn-success btn-sm mr-1 mb-1"><i class='fas fa-edit'></i></a>										
										<?php if (($data['idsucursal'] != 1)) { ?>
										<form action="eliminar_sucursal.php?id=<?php echo $data['idsucursal']; ?>" method="post" class="confirmar d-inline">
											<button class="btn btn-danger btn-sm mr-1 mb-1" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form>
										<?php }	?>		
									</td>
									<?php } else{ ?>
											
									<?php }	?>							
									
								</tr>
						<?php } ?>
						<?php } ?>
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