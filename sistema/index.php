<?php include_once "includes/header.php"; 
?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

	<!-- Page Heading -->	
	<!-- <nav aria-label="breadcrumb mt-5">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>			
				<li class="breadcrumb-item active" aria-current="page">Panel de Control</li>
			</ol>
	</nav> -->
	<?php if ($_SESSION['rol'] == 1) { ?>
	
		<div class="card mb-4">			
			<div class="card-header bg-azul text-white">
				ADMINISTRACION GENERAL     
			</div>
			<div class="card-body bg-gris">	
				<div class="row">					
					<div class="col-xl-12">					
						<div class="row">															
							<div class="col-xl-8">
								<div class="align-items-center justify-content-between">
									<h1 class="h6 mb-0 text-azul"><strong>MOVIMIENTO ECONÓMICO GENERAL</strong></h1>	
								</div>
								<div class="row">									
									<div class="col-4">
										<!-- Card - Ventas Día General-->
											<?php 
												include "../conexion.php";	
												$query_ventasdiag = mysqli_query($conexion, "SELECT SUM(totalfactura)  AS tvdg FROM facturas WHERE fecha = CURRENT_DATE"); 
												$resultado_ventasdiag = mysqli_num_rows($query_ventasdiag);
												$dataventasdiag = mysqli_fetch_assoc($query_ventasdiag);				
												$ventadia = $dataventasdiag['tvdg'];
												if ($ventadia != NULL) {						
													$ventadia = $dataventasdiag['tvdg'];
												} else {
													$ventadia = "0.00";
												}	
											?>
											<a class="col-xl-4 col-md-6 mb-4" style="text-decoration: none;" href="ventas.php">
												<div class="card border-bottom-dark shadow ">
													<div class="card-header bg-gris">														
														<div style="font-size:12px;" class="font-weight-bold text-verde text-uppercase text-center">Ventas Día</div>							
													</div>
													<div class="card-body">
														<div class="row no-gutters align-items-center">
															<div class="col">																
																<div class="h5 mb-0 font-weight-bold text-dark"> Bs. <?php echo $ventadia ?></div>
															</div>
															<div class="col-auto">
																<i class="fas fa-calendar-day fa-2x text-gray-600"></i>
															</div>
														</div>
													</div>					
												</div>
											</a>
										<!-- FIN Card - Ventas Día General-->								
									</div>
									<div class="col-4">
										<!-- Card - Ventas Semana-->
											<?php 
												include "../conexion.php";	
												$query = mysqli_query($conexion, "SELECT SUM(f.totalfactura)  AS tvdg, c.id
																							FROM facturas f
																							INNER JOIN caja c ON f.usuario = c.idusuario
																							WHERE f.fecha >= DATE_SUB(CURRENT_DATE, INTERVAL WEEKDAY(CURRENT_DATE) DAY)"); 
												$resultado = mysqli_num_rows($query);
												$dataventassemana = mysqli_fetch_assoc($query);
												$ventasem = $dataventassemana['tvdg'];
												if ($ventasem != NULL) {						
													$ventasem = $dataventassemana['tvdg'];
												} else {
													$ventasem = "0.00";
												}	
											?>
											<a class="col-xl-4 col-md-6 mb-4" style="text-decoration: none;" href="ventas.php">
												<div class="card border-bottom-dark shadow">
													<div class="card-header bg-gris">														
														<div style="font-size:12px;" class="font-weight-bold text-primary text-uppercase text-center">Ventas - Semana</div>							
													</div>
													<div class="card-body">
														<div class="row no-gutters align-items-center">
															<div class="col mr-2">																
																<div class="h5 mb-0 font-weight-bold text-dark">Bs. <?php echo $ventasem; ?></div>
															</div>
															<div class="col-auto">
																<i class="fas fa-calendar-week fa-2x text-gray-600"></i>
															</div>
														</div>
													</div>
												</div>
											</a>
										<!-- FIN Card - Ventas Semana-->
									</div>
									<div class="col-4">
										<!-- Card - Ventas Mensuales -->
											<?php 
												include "../conexion.php";	
												$query = mysqli_query($conexion, "SELECT SUM(f.totalfactura)  AS tvdg, c.id
																							FROM facturas f
																							INNER JOIN caja c ON f.usuario = c.idusuario
																							WHERE MONTH(fecha) = MONTH(CURRENT_DATE)"); 
												$resultado = mysqli_num_rows($query);
												$dataventasmes = mysqli_fetch_assoc($query);
												$ventasmes = $dataventasmes['tvdg'];
													if ($ventasmes != NULL) {						
														$ventasmes = $dataventasmes['tvdg'];
													} else {
														$ventasmes = "0.00";
													}	
											?>
											<a class="col-xl-4 col-md-6 mb-4" style="text-decoration: none;" href="ventas.php">
												<div class="card border-bottom-dark shadow ">
													<div class="card-header bg-gris">														
														<div style="font-size:12px;" class="font-weight-bold text-celeste text-uppercase text-center">Ventas - Mes</div>							
													</div>
													<div class="card-body">
														<div class="row no-gutters align-items-center">
															<div class="col mr-2">															
																<div class="h5 mb-0 font-weight-bold text-dark">Bs. <?php echo $ventasmes; ?></div>
															</div>
															<div class="col-auto">
																<i class="fas fa-calendar fa-2x text-gray-600"></i>
															</div>
														</div>
													</div>
												</div>
											</a>
										<!-- Card - Ventas Mensuales -->
									</div>
								</div>
								<div class="align-items-center justify-content-between">
									<h1 class="h6 mb-0 text-azul"><strong>ALERTAS GENERALES</strong></h1>	
								</div>
								<div class="row">									
									<div class="col-3">
										<!-- Card - Prod. pocas existencias -->
											<?php 
											include "../conexion.php";	
											$query_existenciabaja = mysqli_query($conexion, "SELECT s.saldo, s.fechavencimiento,
																					m.cantidadminima, m.nombre
																					FROM stock s
																					INNER JOIN medicamento m ON s.idmedicamento = m.idmedicamento
																					WHERE saldo <= cantidadminima"); 
											$resultado_existenciabaja = mysqli_num_rows($query_existenciabaja);										
											?>
											<a class="col-xl-3 col-md-6 mb-4" style="text-decoration: none;" href="lista_bajaexistencia.php">
												<div class="card border-bottom-dark shadow">
													<div class="card-header bg-gris">														
														<div style="font-size:12px;" class="font-weight-bold text-cafe text-uppercase text-center">Baja Existencia</div>							
													</div>
													<div class="card-body">
														<div class="row no-gutters align-items-center">
															<div class="col mr-2">																
																<div class="h5 mb-0 font-weight-bold text-cafe text-center"><?php echo $resultado_existenciabaja;?></div>
															</div>
															<div class="col-auto">
																<i class="fas fa-arrow-circle-down fa-2x text-gray-600"></i>
															</div>
														</div>
													</div>
												</div>
											</a>
										<!-- FIN Card - Prod. pocas existencias -->														
									</div>
									<div class="col-3">
										<!-- Card - Prod. Fecha de Vencimiento -->
											<?php 
											include "../conexion.php";	
											$query_vencimiento = mysqli_query($conexion, "SELECT *
																						FROM stock
																						WHERE MONTH(fechavencimiento) = MONTH(CURRENT_DATE)"); 
											$resultado_vencimiento = mysqli_num_rows($query_vencimiento);										
											?>
											
											<a class="col-xl-3 col-md-6 mb-4" style="text-decoration:none;" href="lista_vencimiento.php">
													<div class="card border-bottom-dark shadow ">
														<div class="card-header bg-gris">														
															<div style="font-size:12px;" class="font-weight-bold text-rojo text-uppercase text-center">vencimiento</div>							
														</div>
														<div class="card-body">
															<div class="row no-gutters align-items-center">
																<div class="col mr-2">																
																	<div class="h5 mb-0 font-weight-bold text-rojo text-center"> <?php echo $resultado_vencimiento;?></div>
																</div>
																<div class="col-auto">
																	<i class="fas fa-pills fa-2x text-gray-600"></i>
																</div>
															</div>
														</div>
													</div>
											</a>
										<!-- Card - Prod. Fecha de Vencimiento -->	
										
									</div>
									<div class="col-3">
										<!-- Card - Prod. Fecha de Vencimiento -->
											<?php 
											include "../conexion.php";	
											$query_caduco = mysqli_query($conexion, "SELECT *
																					FROM stock
																					WHERE MONTH(fechavencimiento) <= MONTH(CURRENT_DATE)"); 
											$resultado_caduco = mysqli_num_rows($query_caduco);										
											?>	
											<a class="col-xl-3 col-md-6 mb-4" style="text-decoration:none;" href="lista_medcaducados.php">
													<div class="card border-bottom-dark shadow ">
														<div class="card-header bg-gris">														
															<div style="font-size:12px;" class="font-weight-bold text-azul text-uppercase text-center">Caducos</div>							
														</div>
														<div class="card-body">
															<div class="row no-gutters align-items-center">
																<div class="col mr-2">																
																	<div class="h5 mb-0 font-weight-bold text-azul text-center"> <?php echo $resultado_caduco; ?></div>
																</div>
																<div class="col-auto">
																	<i class="fas fa-tablets fa-2x text-gray-600"></i>
																</div>
															</div>
														</div>
													</div>
											</a>
										<!-- Card - Prod. Fecha de Vencimiento -->	
									</div>
								</div>
							</div>
							<div class="col-xl-4">								
								<div class="row">
									<div class="row col-12">
										<div class="panel panel-bd lobidisable">
											<div class="panel-heading">
												<div class="panel-title">
												<h1 class="h6 mb-4 text-azul"><strong>INFORME DIARIO</strong></h1>
												</div>
											</div>
											<div class="panel-body">
												<div class="message_inner">
													<div class="message_widgets">														
														<table class="table table-bordered table-striped table-sm table-hover">
														<tbody>
															<tr class="bg-dark text-center" style="font-size:14px;">
																<th class="text-white">Item </th>
																<th class="text-white" >Monto BS.</th>
															</tr>
															<tr>
																<th >Ventas</th>
																<td class="text-left">Bs. <?php echo $ventadia ?></td>
															</tr>
															<tr>
															<?php 
																include "../conexion.php";	
																$query_egresodia = mysqli_query($conexion, "SELECT SUM(monto) AS monto FROM egresos WHERE fecha = CURRENT_DATE"); 
																$resultado_egresodia = mysqli_num_rows($query_egresodia);
																$dataegresodia = mysqli_fetch_assoc($query_egresodia);				
																$egresodia = $dataegresodia['monto'];
																if ($egresodia != NULL) {						
																	$egresodia = $dataegresodia['monto'];
																} else {
																	$egresodia = "0.00";
																}	
															?>
																<th>Gastos</th>
																<td class="text-left">Bs. <?php echo $egresodia ?></td>
															</tr>															
															<tr>


																<th class="bg-verde text-white">Total </th>
																<td class="bg-verde text-white text-center font-weight-bold">Bs.  <?php echo $ventadia - $egresodia?></td>
															</tr>
															
														</tbody></table>
													</div>
												</div> 
												
											</div>
										</div>
									</div>								
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Seccion  Resumen Registros  -->	
					<div class="row ">					
						<div class="align-items-center justify-content-between mb-4">
							<h1 class="h6 mb-0 text-azul"><strong>REGISTROS</strong></h1>	
						</div>

						<!-- Card - Clientes -->
							<a class="col-xl-2 col-md-6 mb-4" style="text-decoration:none;" href="lista_cliente.php">
								<div class="card border-bottom-success shadow h-100">
									<div class="card-header bg-gris">														
										<div style="font-size:12px;" class="font-weight-bold text-verde text-uppercase text-center">Clientes</div>							
									</div>
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-verde text-center"><?php echo $data['clientes']; ?></div>
											</div>
											<div class="col-auto">
												<i class="fas fa-users fa-2x text-gray-600"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<!-- Fin card clientes-->

						<!-- Card - Proveedores -->
							<a class="col-xl-2 col-md-6 mb-4" style="text-decoration:none;" href="lista_proveedor.php">
								<div class="card border-bottom-danger shadow h-100 ">
									<div class="card-header bg-gris">														
										<div style="font-size:12px;" class="font-weight-bold text-rojo text-uppercase text-center">Laboratorios</div>							
									</div>
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">												
												<div class="h5 mb-0 font-weight-bold text-rojo text-center"><?php echo $data['proveedores']; ?></div>
											</div>
											<div class="col-auto">								
												<i class="fas fa-hospital-user fa-2x text-gray-600"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<!-- Fin card proveedores-->

						<!-- Card - Medicamentos --> 
							<a class="col-xl-2 col-md-6 mb-4" style="text-decoration:none;" href="lista_medicamentos.php">
								<div class="card border-bottom-info shadow">
										<div class="card-header bg-gris">														
											<div style="font-size:12px;" class="font-weight-bold text-celeste text-uppercase text-center">Med. Registrados</div>							
										</div>
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 mr-3 font-weight-bold text-celeste text-center"><?php echo $data['medicamentos']; ?></div>
											</div>
											<div class="col-auto">
												<i class="fas fa-tablets fa-2x text-gray-600"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<!-- din card medicamentos-->

						<!-- Card - Medicamentos con stock --> 								
							<a class="col-xl-2 col-md-6 mb-4" style="text-decoration:none;" href="stock.php">
								<div class="card border-bottom-primary shadow">
									<div class="card-header bg-gris">														
											<div style="font-size:12px;" class="font-weight-bold text-azul text-uppercase text-center">Med. Stock</div>							
										</div>
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 mr-3 font-weight-bold text-azul text-center"><?php echo $data['meds']; ?></div>
											</div>
											<div class="col-auto">
												<i class="fas fa-pills fa-2x text-gray-600"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<!-- din card Medicamentos con stock-->

						<!-- Card - Sucursales -->
							<a class="col-xl-2 col-md-6 mb-4" style="text-decoration:none;" href="lista_sucursales.php">
								<div class="card border-bottom-secondary shadow">
									<div class="card-header bg-gris">														
											<div style="font-size:12px;" class="font-weight-bold text-dark text-uppercase text-center">Sucursales</div>							
										</div>
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 mr-3 font-weight-bold text-secondary text-center"><?php echo $data['suc']; ?></div>
											</div>
											<div class="col-auto">
												<i class="fas fa-hospital fa-2x text-gray-600"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<!-- fin cadr sucursales-->

						<!-- Card - Usuarios -->
							<a class="col-xl-2 col-md-6 mb-4" style="text-decoration:none;" href="lista_usuarios.php">
								<div class="card border-bottom-warning shadow">
									<div class="card-header bg-gris">														
										<div style="font-size:12px;" class="font-weight-bold text-cafe text-uppercase text-center">Usuarios</div>							
									</div>
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-cafe text-center"><?php echo $data['usuarios']; ?></div>
											</div>
											<div class="col-auto">
												<i class="fas fa-user-nurse fa-2x text-gray-600"></i>
											</div>
										</div>
									</div>
								</div>
							</a>		
						<!-- fin card usuarios-->

						<!-- Card - Lotes -->
							<a class="col-xl-2 col-md-6 mb-4" style="text-decoration:none;" href="lista_lote.php">
								<div class="card border-bottom-primary shadow">
									<div class="card-header bg-gris">														
										<div style="font-size:12px;" class="font-weight-bold text-azul text-uppercase text-center">Lotes</div>							
									</div>
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-azul text-center"><?php echo $data['lotes']; ?></div>
											</div>
											<div class="col-auto">
												<i class="fas fa-boxes fa-2x text-gray-600"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<!-- fin card lotes-->

						<!-- Card - Cajas -->
							<a class="col-xl-2 col-md-6 mb-4" style="text-decoration:none;" href="registro_caja.php">
								<div class="card border-bottom-success shadow">
									<div class="card-header bg-gris">														
										<div style="font-size:12px;" class="font-weight-bold text-verde text-uppercase text-center">Cajas</div>							
									</div>
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-verde text-center"><?php echo $data['numcajas']; ?></div>
											</div>
											<div class="col-auto">
												<i class="fas fa-cash-register fa-2x text-gray-600"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<!-- Fin card cajas -->
					</div>
				<!-- Seccion  Resumen Registros  -->	
			</div>			
		</div>
	
	<?php } ?>
	
	<?php if ($_SESSION['rol'] != 1) { ?>												
		<div class="card mb-4">			
			<div class="card-header bg-azul text-white">
			ADMINISTRACION SUCURSAL     
			</div>
			<div class="card-body">	
				<div class="row">					
					<div class="col-xl-12">					
						<div class="row">															
							<div class="col-xl-8">
								<div class="align-items-center justify-content-between">
									<h1 class="h6 mb-0 text-azul"><strong>MOVIMIENTO ECONÓMICO </strong></h1>	
								</div>
								<div class="row">									
									<div class="col-4">
										<!-- Card - Ventas Día Sucursal-->
											<?php 
												include "../conexion.php";
												$idsuc = $_SESSION['idsuc'];	
												$query = mysqli_query($conexion, "SELECT SUM(f.totalfactura)  AS tvdg, u.idsucursal
																				FROM facturas f
																				INNER JOIN usuario u ON f.usuario = u.idusuario
																				WHERE fecha = CURDATE() AND u.idsucursal = $idsuc"); 
												$resultado = mysqli_num_rows($query);
												$dataventadias = mysqli_fetch_assoc($query);
												$ventadias = $dataventadias['tvdg'];
												if ($ventadias != NULL) {						
													$ventadias = $dataventadias['tvdg'];
												} else {
													$ventadias = "0.00";
												}
											?>
											<a class="col-xl-3 col-md-6 mb-4" style="text-decoration:none;" href="ventas.php">
												<div class="card border-left-success shadow py-2">
													<div class="card-body">
														<div class="row no-gutters align-items-center">
															<div class="col mr-2">
																<div style="font-size:12px;" class="font-weight-bold text-verde text-uppercase mb-1">Ventas Día</div>
																<div class="h5 mb-0 font-weight-bold text-dark">Bs. <?php echo $ventadias; ?></div>
															</div>
															<div class="col-auto">
																<i class="fas fa-coins fa-2x text-gray-600"></i>
															</div>
														</div>
													</div>
												</div>
											</a>
										<!-- Card - Ventas Día Sucursal-->								
									</div>
									<div class="col-4">
										<!-- Card - Ventas Día Caja-Usuario -->
											<?php 
												include "../conexion.php";	
												$idcaja = $_SESSION['idcaja'];	
												$query = mysqli_query($conexion, "SELECT SUM(f.totalfactura)  AS tvdg, c.id
																							FROM facturas f
																							INNER JOIN caja c ON f.usuario = c.idusuario
																							WHERE fecha = CURDATE() AND c.id = $idcaja"); 
												$resultado = mysqli_num_rows($query);
												$dataventacaja = mysqli_fetch_assoc($query);
												$ventadiac = $dataventacaja['tvdg'];
												if ($ventadiac != NULL) {						
													$ventadiac = $dataventacaja['tvdg'];
												} else {
													$ventadiac = "0.00";
												}
												?>
											<a class="col-xl-3 col-md-6 mb-4" style="text-decoration:none;" href="ventas.php">
												<div class="card border-left-danger shadow py-2">
													<div class="card-body">
														<div class="row no-gutters align-items-center">
															<div class="col mr-2">
																<div style="font-size:12px;" class="font-weight-bold text-rojo text-uppercase mb-1">Ventas Día Caja</div>
																<div class="h5 mb-0 font-weight-bold text-dark ">Bs. <?php echo $ventadiac; ?></div>
															</div>
															<div class="col-auto">
																<i class="fas fa-cash-register fa-2x text-gray-600"></i>
															</div>
														</div>
													</div>
												</div>
											</a>
										<!-- fin Card - Ventas Día Caja-Usuario -->	
									</div>
									<div class="col-4">
										
									</div>
								</div>
								<div class="align-items-center justify-content-between">
									<h1 class="h6 mb-0 text-rojo"><strong>ALERTAS </strong></h1>	
								</div>
								<div class="row">									
									<div class="col-4">
										<!-- Card - Prod. pocas existencias -->
											<?php 
											include "../conexion.php";	
											$query_existenciabaja = mysqli_query($conexion, "SELECT s.saldo, s.fechavencimiento,
																					m.cantidadminima, m.nombre
																					FROM stock s
																					INNER JOIN medicamento m ON s.idmedicamento = m.idmedicamento
																					WHERE saldo <= cantidadminima AND idsuc = $idsuc"); 
											$resultado_existenciabaja = mysqli_num_rows($query_existenciabaja);										
											?>
											<a class="col-xl-3 col-md-6 mb-4" style="text-decoration: none;" href="lista_bajaexistencia.php">
												<div class="card border-left-dark shadow py-2">
													<div class="card-body">
														<div class="row no-gutters align-items-center">
															<div class="col mr-2">
																<div style="font-size: 12px;" class="font-weight-bold text-cafe text-uppercase mb-1">Baja Existencia</div>
																<div class="h5 mb-0 font-weight-bold text-cafe text-center"><?php echo $resultado_existenciabaja;?></div>
															</div>
															<div class="col-auto">
																<i class="fas fa-arrow-circle-down fa-2x text-gray-600"></i>
															</div>
														</div>
													</div>
												</div>
											</a>
										<!-- FIN Card - Prod. pocas existencias -->														
									</div>
									
									<div class="col-4">
										<!-- Card - Prod. Fecha de Vencimiento -->
											<?php 
											include "../conexion.php";	
											$idsuc= $_SESSION['idsuc'];
											$query_vencimiento = mysqli_query($conexion, "SELECT *
																						FROM stock
																						WHERE (MONTH(fechavencimiento) = MONTH(CURRENT_DATE)) AND 
																						(idsuc = $idsuc)"); 
											$resultado_vencimiento = mysqli_num_rows($query_vencimiento);										
											?>
											
											<a class="col-xl-3 col-md-6 mb-4" style="text-decoration:none;" href="lista_vencimiento.php">
													<div class="card border-left-dark shadow py-2">
														<div class="card-body">
															<div class="row no-gutters align-items-center">
																<div class="col mr-2">
																	<div style="font-size: 12px;" class="font-weight-bold text-rojo text-uppercase mb-1 small-box-footer">VENCIMIENTO</div>
																	<div class="h5 mb-0 font-weight-bold text-rojo text-center"> <?php echo $resultado_vencimiento;?></div>
																</div>
																<div class="col-auto">
																	<i class="fas fa-pills fa-2x text-gray-600"></i>
																</div>
															</div>
														</div>
													</div>
											</a>
										
										<!-- Card - Prod. Fecha de Vencimiento -->	
									</div>
									<div class="col-4">
										<!-- Card - Prod. Fecha de Vencimiento -->
										<?php 
											include "../conexion.php";	
											$idsuc= $_SESSION['idsuc'];
											$query_caduco = mysqli_query($conexion, "SELECT *
																						FROM stock
																						WHERE (MONTH(fechavencimiento) <= MONTH(CURRENT_DATE)) AND 
																						(idsuc = $idsuc)"); 
											$resultado_caduco = mysqli_num_rows($query_caduco);										
											?>
									
											<a class="col-xl-3 col-md-6 mb-4" style="text-decoration:none;" href="lista_medcaducados.php">
													<div class="card border-left-dark shadow py-2">
														<div class="card-body">
															<div class="row no-gutters align-items-center">
																<div class="col mr-2">
																	<div style="font-size: 12px;" class="font-weight-bold text-azul text-uppercase mb-1 small-box-footer">CADUCOS</div>
																	<div class="h5 mb-0 font-weight-bold text-azul text-center"> <?php echo $resultado_caduco; ?></div>
																</div>
																<div class="col-auto">
																	<i class="fas fa-pills fa-2x text-gray-600"></i>
																</div>
															</div>
														</div>
													</div>
											</a>
										<!-- Card - Prod. Fecha de Vencimiento -->	
									</div>
								</div>
							</div>
							<div class="col-xl-4">								
								<div class="row">
									<div class="row col-12">
										<div class="panel panel-bd lobidisable">
											<div class="panel-heading">
												<div class="panel-title">
												<h1 class="h6 mb-4 text-azul"><strong>INFORME DIARIO</strong></h1>
												</div>
											</div>
											<div class="panel-body">
												<div class="message_inner">
													<div class="message_widgets">														
														<table class="table table-bordered table-striped table-sm table-hover">
														<tbody>
															<tr class="bg-dark text-center" style="font-size:14px;">
																<th class="text-white">Item </th>
																<th class="text-white" >Monto BS.</th>
															</tr>
															<tr>
																<th >Ventas</th>
																<td class="text-left">Bs. <?php echo $ventadias; ?></td>
															</tr>
															<tr>
															<?php 
																include "../conexion.php";	
																$idcaja = $_SESSION['idcaja'];	
																$queryegresossuc = mysqli_query($conexion, "SELECT SUM(e.monto)  AS monto, c.id
																								FROM egresos e
																								INNER JOIN caja c ON e.idusuario = c.idusuario
																								WHERE fecha = CURDATE() AND c.id = $idcaja"); 
																$resultadoegresossuc = mysqli_num_rows($queryegresossuc);
																$dataegresossuc = mysqli_fetch_assoc($queryegresossuc);
																$egresossucdia = $dataegresossuc['monto'];
																if ($egresossucdia != NULL) {						
																	$egresossucdia = $dataegresossuc['monto'];
																} else {
																	$egresossucdia = "0.00";
																}
																?>
																<th>Gastos</th>
																<td class="text-left">Bs. <?php echo $egresossucdia ?></td>
															</tr>															
															<tr>
																<th class="bg-verde text-white">Total </th>
																<td class="bg-verde text-white text-center font-weight-bold">Bs <?php echo $ventadias - $egresossucdia?></td>
															</tr>
															
														</tbody></table>
													</div>
												</div> 
												
											</div>
										</div>
									</div>								
								</div>
							</div>
						</div>
					</div>
				</div>				
			</div>			
		</div>
		<?php } ?>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<?php include_once "includes/footer.php"; ?>