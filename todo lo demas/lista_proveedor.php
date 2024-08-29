<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

	<!-- Page Heading -->	
<!-- 	<nav aria-label="breadcrumb">
  		<ol class="breadcrumb bg-azul">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-white"></a></i></li>
			<li class="breadcrumb-item"><a href="lista_proveedor.php"><i class="fas fa-building text-white"></a></i></li>
    		<li class="breadcrumb-item active text-gray-500" aria-current="page">Proveedores</li>
  		</ol>
	</nav>	 -->
	<!-- Page Heading -->
	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">Lista Proveedores</h1>        		
				<a href="registro_proveedor.php" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i>Nuevo</a>				
      		</div>
    	</div>    
    <div class="card-body">
		<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered" id="table">
					<thead class="thead-dark">
						<tr class="text-center aligin-middle" style="font-size:12px;">
							<th>ID</th>
							<th width="350px">PROVEEDOR</th>
							<th>CONTACTO</th>
							<th>TELEFONO</th>
							<?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>	
							<th>ACCIONES</th>	
							<?php } ?>						
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM proveedor");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr class="aligin-middle" style="font-size:14px;">
									<td class="text-center"><?php echo $data['codproveedor']; ?></td>
									<td width="350px"><?php echo $data['proveedor']; ?></td>
									<td><?php echo $data['contacto']; ?></td>
									<td><?php echo $data['telefono']; ?></td>
									<?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
									<td class="text-center">									
										<a href="detalle_proveedor.php?id=<?php echo $data['codproveedor']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-eye'></i></a>										
										<a href="editar_proveedor.php?id=<?php echo $data['codproveedor']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>
										<?php if ($_SESSION['rol'] == 1)  { ?>
										<form action="eliminar_proveedor.php?id=<?php echo $data['codproveedor']; ?>" method="post" class="confirmar d-inline">
											<button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form>
										<?php } ?>										
									</td>	
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