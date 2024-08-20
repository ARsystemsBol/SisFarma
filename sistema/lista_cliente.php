<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

	<!-- Page breadcrumb -->	
	<!-- <nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	        	
        	<li class="breadcrumb-item"><a href="lista_cliente.php"><i class="fa fa-users text-info"></a></i></li>			
    		<li class="breadcrumb-item active" aria-current="page">Lista Clientes</li>
  		</ol>
  	</nav> -->
  	<!-- End Page breadcrumb -->

	<!-- Page Heading -->
	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">Clientes</h1>        		
				<a href="registro_cliente.php" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i>Nuevo</a>				
      		</div>
    	</div>    
    <div class="card-body">
		<!-- Page Heading -->
		<div class="row">
		<div class="col-lg-10 m-auto">
			<div class="table-responsive">
				<table class="table table-sm table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr class="text-center aligin-middle" style="font-size: 12px;">
							<th>ID</th>
							<th>NIT-CI</th>
							<th>NOMBRE</th>
							<th>TELEFONO</th>
							<th>DIRECCIÓN</th>							
							<th>ACCIONES</th>							
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM cliente");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr style="font*-size:14px;">
									<td class="text-center"><?php echo $data['idcliente']; ?></td>
									<td><?php echo $data['nit_ci']; ?></td>
									<td><?php echo $data['nombre']; ?></td>
									<td><?php echo $data['telefono']; ?></td>
									<td><?php echo $data['direccion']; ?></td>								
									<td class="text-center">
										<a href="editar_cliente.php?id=<?php echo $data['idcliente']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>
										<?php if ($data['idcliente'] != 1)  { ?>
										<form action="eliminar_cliente.php?id=<?php echo $data['idcliente']; ?>" method="post" class="confirmar d-inline">
											<button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form>
										<?php } ?>			
									</td>									
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