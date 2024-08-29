<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

	<!-- Page Heading -->
<!-- 	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	
			<li class="breadcrumb-item"><a href="lista_medicamentos.php"><i class="fas fa-tablets text-info"></a></i></li>
			<li class="breadcrumb-item"><a href="lista_formafarmaceutica.php"><i class="fas fa-list text-info"></a></i></li>			
			<li class="breadcrumb-item active" aria-current="page">Lista Ventas</li>
		</ol>
	</nav> -->
	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">VENTAS</h1>        		
				<a href="registro_ventas.php" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i>Nueva</a>				
      		</div>
    	</div>    
    <div class="card-body">
			<!-- Page Heading -->

		<div class="row">
			<div class="col-lg-12 m-auto">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-sm" id="table">
						<thead class="thead-dark">
							<tr class="text-center">
								<th>Id</th>
								<th>Fecha</th>
								<th>NIT</th>
								<th>Cliente</th>
								<th>Total</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php
							require "../conexion.php";
							$user =  $_SESSION['idUser'];
							$query = mysqli_query($conexion, "SELECT f.nofactura, f.fecha, f.codcliente, f.totalfactura, f.estado,
																c.nombre, c.nit_ci
																FROM facturas f
																INNER JOIN cliente c ON f.codcliente = c.idcliente
																WHERE f.usuario = $user
																ORDER BY nofactura DESC");
							mysqli_close($conexion);
							$cli = mysqli_num_rows($query);

							if ($cli > 0) {
								while ($dato = mysqli_fetch_array($query)) {
							?>
									<tr>
										<td class="text-center"><?php echo $dato['nofactura']; ?></td>
										<td><?php echo $dato['fecha']; ?></td>
										<td><?php echo $dato['nit_ci']; ?></td>
										<td><?php echo $dato['nombre']; ?></td>
										<td class="text-center"><?php echo $dato['totalfactura']; ?></td>
										<td class="text-center">
											<button type="button" class="btn btn-primary btn-sm view_facturas" cl="<?php echo $dato['codcliente'];  ?>" f="<?php echo $dato['nofactura']; ?>">Detalle</button>
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