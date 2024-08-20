<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

<!-- Page breadcrumb -->	
<!-- <nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	
        <li class="breadcrumb-item"><a href="lista_medicamentos.php"><i class="fas fa-tablets text-info"></a></i></li>			
    		<li class="breadcrumb-item active" aria-current="page">Lista Medicamentos</li>
  		</ol>
</nav> -->
<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between mb-1">
        		<h1 class="h6 mb-0 text-uppercase mr-5">Lista Medicamentos</h1>     			
      		</div>
    	</div>    
    <div class="card-body">
<!-- Page Heading -->	
	<div class="d-sm-flex align-items-center mb-4">
		<a href="registro_medicamento.php" class="btn btn-success btn-sm mr-2"><i class="fas fa-plus mr-2"></i><i class="fas fa-pills mr-1"></i>Medicamento</a>
		<a href="registro_pactivo.php" class="btn btn-info btn-sm mr-2"><i class="fas fa-plus mr-2"></i><i class="fas fa-vial mr-1"></i>Principio Activo</a>
		<a href="registro_formafarmaceutica.php" class="btn btn-warning btn-sm mr-2 text-white"><i class="fas fa-plus mr-2"></i><i class="fas fa-capsules mr-1"></i>Forma Farmaceutica</a>
		<a href="registro_accionterapeutica.php" class="btn btn-secondary btn-sm mr-2"><i class="fas fa-plus mr-2"></i><i class="fas fa-stethoscope mr-1"></i>Accion Terapéutica</a>
		<a href="registro_unidades.php" class="btn btn-primary btn-sm mr-2"><i class="fas fa-plus mr-2"></i><i class="fas fa-vials mr-1"></i>Unidad</a>
	</div>

<div class="row">
	<div class="col-lg-12">
		<div class="table-responsive">
			<table class="table table-striped table-sm table-bordered" id="table">
				<thead class="thead-dark">
					<tr class="text-center align-middle" style="font-size:12px">
						<th width="30">ID</th>						
						<th width="500">NOMBRE  -  FORMA  -  CONCENTRACIÓN  -  A. TERAPÉUTICA  -  P. ACTIVO</th>
						<th width="100">LAB.</th>					
						<th width="100">USO</th>							
						<?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
						<th>ACCIONES</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php
					include "../conexion.php";
					$query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, 
															m.concentracion, m.stock,
															pa.medicamento AS despactivo,
															f.nombre AS desforma,
															ate.nombre AS desaterapeutica,
															un.abreviatura AS desunidad,
															u.descripcion AS desuso,
															p.lab AS deslaboratorio														
															FROM medicamento m
															INNER JOIN pactivo pa ON m.idpactivo = pa.id
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
															INNER JOIN unidades un ON m.idunidad = un.idunidad
															INNER JOIN uso u ON pa.uso = u.id
															INNER JOIN proveedor p ON m.laboratorio = p.codproveedor
															ORDER BY m.nombre ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
							$data['nombrecompleto'] = $data['nombre'].' ' . $data['desforma'].' ' . $data['concentracion'].' ' . $data['desunidad']. ' ' . $data['desaterapeutica']. ' ' . $data['despactivo'];
							?>							
							<tr>
								<td width="30" class="text-center align-middle" style="font-size:14px"><?php echo $data['idmedicamento']; ?></td>								
								<td width="500" class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'];?></td>								
								<td width="100" class="align-middle" style="font-size:14px"><?php echo $data['deslaboratorio']?></td>
								<td width="100" class="align-middle" style="font-size:14px"><?php echo $data['desuso']?></td>									
									<?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
								<td class="text-center align-middle" style="font-size:14px">
									<a href="detalle_medicamento.php?id=<?php echo $data['idmedicamento']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye fa-sm" ></i></a>
									<?php if ($_SESSION['rol'] == 1) { ?>
									<a href="editar_medicamento.php?id=<?php echo $data['idmedicamento']; ?>" class="btn btn-success btn-sm "><i class='fas fa-edit fa-sm'></i></a>
									<form action="eliminar_medicamento.php?id=<?php echo $data['idmedicamento']; ?>" method="post" class="confirmar d-inline ">
										<button class="btn btn-danger btn-sm " type="submit"><i class='fas fa-trash-alt fa-sm'></i> </button>
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
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<?php include_once "includes/footer.php"; ?>