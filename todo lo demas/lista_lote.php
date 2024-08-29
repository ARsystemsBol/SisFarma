<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">
<!-- Page breadcrumb -->	
<!-- <nav aria-label="breadcrumb">
  	<ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	
        <li class="breadcrumb-item"><a href="lista_lote.php"><i class="fas fa-boxes text-info"></a></i></li>					
    	<li class="breadcrumb-item active" aria-current="page">Lista Lotes</li>
  	</ol>
</nav>
 -->
<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase mr-5">Listado de Lotes</h1>     			
      		</div>
    	</div>    
    <div class="card-body">
			<!-- Page Heading -->	
	<div class="d-sm-flex align-items-center mb-4">
		<a href="registro_lote.php" class="btn btn-primary btn-sm">Registrar Nuevo</a>
		<a href="stock.php" class=" ml-3 btn btn-warning btn-sm">Ver Stock</a>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-sm table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr style="font-size: 12px;" class="text-center aligin-middle">
							<th>ID</th>
							<th>DESCRIPCION</th>
							<th>FECHA </th>
							<th>PROVEEDOR</th>
							<th>TOTAL COMPRA</th>							
							<th>ESTADO</th>							
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT lote.nolote, lote.descripcion as deslote, lote.fecha, lote.codproveedor, lote.totalcompra, lote.estado, proveedor.proveedor, estado_lote.descripcion
														FROM ((lote
														INNER JOIN proveedor ON lote.codproveedor= proveedor.codproveedor)
														INNER JOIN estado_lote ON lote.estado= estado_lote.id);");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr class="aligin-middle" style="font-size:14px;">
									<td class="text-center"><?php echo $data['nolote']; ?></td>
									<td><?php echo $data['deslote']; ?></td>
									<td class="text-center"><?php echo $data['fecha']; ?></td>
									<td><?php echo $data['proveedor']; ?></td>
									<td class="text-center"><?php echo $data['totalcompra']; ?></td>									
									<td class="text-center"><?php echo $data['descripcion']; ?></td>									
										<?php if ($_SESSION['rol'] == 1) { ?>
									<td class="text-center">
									
										<button data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Nota de Ingreso" type="button" class="btn btn-primary btn-sm view_notaingreso" p="<?php echo $data['codproveedor'];  ?>" l="<?php echo $data['nolote']; ?>"><i class='fas fa-eye'></i></button>										
										<a data-bs-toggle="tooltip" data-bs-placement="top" title="Detalle de Pago" href="editar_medicamento.php?id=<?php echo $data['nolote']; ?>" class="btn btn-success btn-sm "><i class='fas fa-coins fa-sm'></i></a>
										<form action="eliminar_lote.php?id=<?php echo $data['nolote']; ?>" method="post" class="confirmar d-inline">
											<button hidden data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar" class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form>
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