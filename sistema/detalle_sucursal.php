<?php include_once "includes/header.php"; 
include "../conexion.php";

$idsucursal = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM sucursal WHERE idsucursal = $idsucursal");
$result_sql = mysqli_num_rows($sql);
if($result_sql > 0){
$datasuc = mysqli_fetch_assoc($sql);
}
$dessucursal = $datasuc['nombre'];
?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">
<!-- Page Heading -->	
<!-- 	<nav aria-label="breadcrumb">
  		<ol class="breadcrumb bg-azul">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-white"></a></i></li>
			<li class="breadcrumb-item"><a href="lista_sucursales.php"><i class="fas fa-store text-white"></a></i></li>
			<li class="breadcrumb-item"><a ><i class="fas fa-file text-white"></a></i></li>
    		<li class="breadcrumb-item active text-gray-500" aria-current="page">Detalle Sucursal</li>
  		</ol>
	</nav>	 -->

	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">Detalle - <strong class="text-info" style="font-size: 16px;"> <?php echo $dessucursal;?> </strong> </h1>        		
				<a href="lista_sucursales.php" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left mr-1"></i>Regresar</a>				
      		</div>
    	</div>    
    <div class="card-body">
		<div class="row">
			<div class="col-lg-7">
				<!-- CARD USUARIOS -->
				<div class="card mb-4">
					<div class="card-header bg-verde text-white">
						USUARIOS
					</div>
					<div class="card-body">
						<table class="table table-sm table-striped table-bordered" >
							<thead class="thead-dark">
								<tr class="text-center aligin-middle" style="font-size:12px;">
									<th>ID</th>
									<th>NOMBRE</th>							
									<th>USUARIO</th>
									<th>ROL</th>									
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
																INNER JOIN sucursal s ON u.idsucursal = s.idsucursal
																WHERE u.idsucursal = $idsucursal");
								$result = mysqli_num_rows($query);
								if ($result > 0) {
									while ($data = mysqli_fetch_assoc($query)) { ?>
										<tr>
											<td  style="font-size:14px;" class= text-center><?php echo $data['idusuario']; ?></td>
											<td style="font-size:14px;" ><?php echo $data['nombre']; ?></td>
											<td style="font-size:14px;" ><?php echo $data['usuario']; ?></td>
											<td style="font-size:14px;" ><?php echo $data['rol']; ?></td>											
											<?php if ($_SESSION['rol'] == 1) { ?>
											<td class="text-center">
												<a href="editar_usuario.php?id=<?php echo $data['idusuario']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit fa-sm'></i></a>
												<form action="eliminar_usuario.php?id=<?php echo $data['idusuario']; ?>" method="post" class="confirmar d-inline">
													<button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt fa-sm'></i> </button>
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
			<div class="col-lg-5">
				<!-- CARD VENTAS-->
				<div class="card mb-4">
					<div class="card-header bg-azul text-white">
						VENTAS
					</div>
					<div class="card-body">
						
					</div>
				</div>				
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12">
				<!-- CARD STOCK -->
				<div class="card mb-4">
					<div class="card-header bg-azul text-white">
						STOCK 
					</div>
					<div class="card-body">
						<table class="table table-striped table-sm table-bordered" id="table">
							<thead class="thead-dark">
							<tr class="TEXT-CENTERalign-middle" style="font-size:12px">
								<th>ID</th>						
								<th>NOMBRE</th>
								<th>FECHA VEN.</th>
								<th>STOCK</th>
								<th>LAB.</th>				
								<?php if ($_SESSION['rol'] == 1) { ?>
								<th>VER</th>
								<?php } ?>
							</tr>
							</thead>
							<tbody>
							<?php
							include "../conexion.php";
							
							$query = mysqli_query($conexion, "SELECT s.correlativo, s.fechavencimiento, s.saldo, s.idmedicamento AS vermedicamento,
																m.laboratorio, m.nombre, m. idforma, m.concentracion, m.idunidad,
																p.lab AS deslaboratorio, 
																f.nombre AS desforma,
																u.abreviatura AS desunidad,
																su.nombre AS dessucursal
																FROM stock s
																INNER JOIN medicamento m ON s.idmedicamento = m.idmedicamento
																INNER JOIN proveedor p ON m.laboratorio = p.codproveedor
																INNER JOIN forma f ON m.idforma = f.idforma
																INNER JOIN unidades u ON m.idunidad = u.idunidad
																INNER JOIN sucursal su ON s.idsuc = su.idsucursal
																WHERE idsuc = $idsucursal");
							$result = mysqli_num_rows($query);
							if ($result > 0) {
								while ($data = mysqli_fetch_assoc($query)) { 
								$data['nombrecompleto'] = $data['nombre'].' ' . $data['desforma'].' ' . $data['concentracion'].' ' . $data['desunidad'];
													
								?>							
								<tr>
									<td class="text-center align-middle" style="font-size:14px"><?php echo $data['correlativo']; ?></td>								
									<td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'];?></td>								
									<td class="align-middle" style="font-size:14px"><?php echo $data['fechavencimiento']?></td>
									<td class="align-middle" style="font-size:14px"><?php echo $data['saldo']?></td>			
									<td class="align-middle" style="font-size:14px"><?php echo $data['deslaboratorio']?></td>		
									<td class="text-center align-middle" style="font-size:14px">
										<a href="detalle_medicamento.php?id=<?php echo $data['vermedicamento']; ?>" class="btn btn-success btn-sm  "><i class='fas fa-eye fa-sm'></i></a>
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
	
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<?php include_once "includes/footer.php"; ?>