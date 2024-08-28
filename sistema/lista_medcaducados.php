<?php include_once "includes/header.php";
  include "../conexion.php";
?>

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
      		<div class="d-sm-flex align-items-center justify-content-between mb-2">
        		<h1 class="h6 mb-0 text-uppercase">Lista Medicamentos Caducados</h1>        		
				<a href="index.php" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left mr-1"></i>Volver</a>									
      		</div>
			  <div class="d-sm-flex align-items-center mb-2"> 			  	
			  	<a id="printbe" name="printbe" class="btn btn-info btn-sm mr-2 pl-4 pr-4"><i class="fas fa-print mr-2"></i>Imprimir</a>	    
				<a  id="printbec" name="printbec" class="btn btn-danger btn-sm mr-2 pl-4 pr-4"><i class="fas fa-arrow-left mr-2"></i>Cancelar</a>
				<a  id="printbel" name="printbel" href="#" class="btn btn-secondary btn-sm mr-1 pl-4 pr-4"><i class="fas fa-print mr-2"></i>x Laboratorio</a>	
				<div  id="labprntbel" name="labprntbel" class="col-2">                      
                        <?php
                          $query_prov = mysqli_query($conexion, "SELECT codproveedor, lab 
                                                              FROM proveedor 
                                                              ORDER BY lab ASC");
                          $resultado_prov = mysqli_num_rows($query_prov);           
                        ?>
                        <select id="prov" name="prov" class="form-control form-control-sm">
                            <option value="0">Seleccionar...</option>
                            <?php
                              if ($resultado_prov > 0) {
                              while ($prov = mysqli_fetch_array($query_prov)) {
                                // code...
                            ?>
                            <option value="<?php echo $prov['codproveedor']; ?>">
                                <?php echo $prov['lab']; ?>
                            </option>
                            <?php } } ?>
                        </select>
                    </div>								
      		</div>
    	</div>    
    <div class="card-body">
		<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered" id="table">
					<thead class="thead-dark">
						<tr class="text-center aligin-middle" style="font-size:12px;">
							<th width="30px">ID</th>
							<th width="450px">NOMBRE</th>
							<th width="100px">F. VENCIMIENTO</th>
							<th width="100px">LABORATORIO</th>
							<th width="70px">C. MINIMA</th>
							<th width="40px">STOCK</th>											
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT s.idmedicamento AS id, s.saldo, s.fechavencimiento, s.idsuc,
															m.cantidadminima, m.nombre AS nombremed, m.laboratorio, m.concentracion,
															p.lab, f.nombre AS desforma, u.abreviatura AS desunidad
															FROM stock s
															INNER JOIN medicamento m ON s.idmedicamento = m.idmedicamento 
															INNER JOIN proveedor p ON m.laboratorio = p.codproveedor
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN unidades u ON m.idunidad = u.idunidad
															WHERE s.fechavencimiento  < CURRENT_DATE															
															ORDER BY p.codproveedor ASC,
															s.idmedicamento ASC,
															s.fechavencimiento ASC");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) {
								$data['nombrecompleto'] = $data['nombremed'].' '. $data['desforma'].' '. $data['concentracion'].''. $data['desunidad'];
								?>
								
								<tr class="aligin-middle" style="font-size:14px;">
									<td class="text-center" width="30px" class="text-center"><?php echo $data['id']; ?></td>
									<td width="450px"><?php echo $data['nombrecompleto']; ?></td>
									<td class="text-center" width="100px"><?php echo $data['fechavencimiento']; ?></td>
									<td class="text-center" width="100px"><?php echo $data['lab']; ?></td>
									<td class="text-center" width="70px"><?php echo $data['cantidadminima']; ?></td>
									<td class="text-center" width="40px"><?php echo $data['saldo']; ?></td>	
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