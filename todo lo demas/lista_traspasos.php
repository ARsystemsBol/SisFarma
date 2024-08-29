<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

	<!-- Page Heading -->	
<!-- 	<nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>
			<li class="breadcrumb-item"><a href="lista_traspasos.php"><i class="fas fa-sync-alt text-info"></a></i></li>
    		<li class="breadcrumb-item active" aria-current="page">Traspasos</li>
  		</ol>
	</nav>	 -->
	<!-- Page Heading -->
	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">Lista Traspasos</h1>        		
				<a href="registro_traspaso.php" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i>Nuevo</a>				
      		</div>
    	</div>    
    <div class="card-body">
		<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered" id="table">
					<thead class="thead-dark text-center" style="font-size: 12px;">
						<tr>
							<th>ID</th>
							<th>FECHA</th>
							<th>TIPO</th>
							<th>DESCRIPCION</th>								
							<th>CAPITAL</th>
							<th>ESTADO</th>
							<th>ACCIONES</th>							
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT t.notraspaso, t.fecha, t.notatraspaso, t.capital,
															t.idsucdestino,
															tm.descripcion AS desmovimiento, et.descripcion AS desestado
															FROM traspaso t
															INNER JOIN tipomovimiento tm ON t.idtipomov = tm.idtipomov															
															INNER JOIN estado_traspaso et ON t.idestado = et.id;");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr style="font-size: 12px;">
									<td class="text-center aligin-middle"><?php echo $data['notraspaso']; ?></td>
									<td><?php echo $data['fecha']; ?></td>
									<td><?php echo $data['desmovimiento']; ?></td>
									<td><?php echo $data['notatraspaso']; ?></td> 
									<td><?php echo $data['capital']; ?></td>
									<td><?php echo $data['desestado']; ?></td>									
									<?php if (($_SESSION['rol'] == 1)) { ?>
									<td class="text-center">
									    <button type="button" class="btn btn-success btn-sm view_notaremision" sd="<?php echo $data['idsucdestino'];  ?>" idt="<?php echo $data['notraspaso']; ?>"><i class='fas fa-print'></i></button>											
										<a href="detalle_traspaso.php?id=<?php echo $data['notraspaso']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-clipboard-list'></i></a>
										<!-- <form action="eliminar_traspaso.php?id=<?php echo $data['notraspaso']; ?>" method="post" class="confirmar d-inline">
											<button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form>	 -->									
									</td>
									<?php } else{ ?>
										<td>									
											<a href="detalle_traspaso.php?id=<?php echo $data['notraspaso']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-clipboard-list'></i></a></td>
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