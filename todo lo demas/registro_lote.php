<?php include_once "includes/header.php"; 
include "../conexion.php";
?>
<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">
    <!-- Page breadcrumb -->	
 <!--    <nav aria-label="breadcrumb">
  	    <ol class="breadcrumb">
    	    <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	
            <li class="breadcrumb-item"><a href="lista_lote.php"><i class="fas fa-boxes text-info"></a></i></li>
            <li class="breadcrumb-item"><a href="registro_lote.php"><i class="fas fa-box text-info"></a></i></li>						
    	    <li class="breadcrumb-item active" aria-current="page">Registro Lote</li>
  	    </ol>
    </nav> -->
    <!-- End Page breadcrumb -->	

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <!-- encabezado card-lote --> 
                <div class="card-header bg-azul text-white ">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h1 class="h6 mb-0 text-uppercase">Registrar lote</h1>
                        <a href="lista_lote.php" class="btn btn-danger btn-sm">Regresar</a>
                    </div>
                </div>

                <!-- cuerpo card-lote --> 
                <div class="card-body">
                    <form class="row col-10 m-auto" action="" method="post" autocomplete="off">                            
                            <!-- DESCRIPCION --> 
                            <div class="col-md-6">
                                <label for="descripcionlote" class="form-label">DESCRIPCION</label>
                                <input  autofocus type="text" class="form-control form-control-sm" name = "descripcionlote" id="descripcionlote">
                            </div>                         
                            
                            <!-- ESTADO--> 
                            <div class="col-4 mb-4">
                                <label for="estadolote" class="form-label">PAGO <span class="text-danger fs-6 fst-italic ml-2">Clic para seleccionar...</span></label>                                      
                                <select id="estadolote" name="estadolote" class="form-control form-control-sm">
                                    <option ></option>
                                    <option value="2">PENDIENTE</option>
                                    <option value="1">PAGADO</option>
                                </select>
                            </div>
                            <!-- PROVEEDOR--> 
                            <div class="col-8 mb-4 ">
                                <label>PROVEEDOR <span class="text-danger fs-6 fst-italic ml-2">Clic para seleccionar...</span>  </label>                            
                                <?php
                                $query_proveedorlote = mysqli_query($conexion, "SELECT codproveedor, proveedor FROM proveedor ORDER BY codproveedor ASC");
                                $resultado_proveedorlote = mysqli_num_rows($query_proveedorlote);
                                ?>
                                <select id="proveedorlote" name="proveedorlote" class="form-control form-control-sm">
                                    <option></option>
                                    <?php
                                    if ($resultado_proveedorlote > 0) {
                                    while ($proveedorlote = mysqli_fetch_array($query_proveedorlote)) {
                                        // code...
                                    ?>
                                        <option value="<?php echo $proveedorlote['codproveedor']; ?>"><?php echo $proveedorlote['proveedor']; ?></option>
                                    <?php
                                    }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                             <!-- DESCRIPCION --> 
                             <!-- <div class="col-md-5">
                                <label for="prueba" class="form-label">prueba</label>
                                <input type="text" class="form-control form-control-sm" name = "prueba" id="prueba">
                            </div> -->
                            <div class="row col-12 mb-2">
                                <div class="col-lg-6">
                                  <!--   Button trigger modal -->
                                    <button  id="loco" type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-search mr-4"></i> Buscar Medicamento...
                                    </button> 
                                </div>
                            </div>
                            <div class="row">                  
                            <div class="col-lg-6">                                
                                <div id="acciones_reglote" class="form-group">
                                    <a style="display:none;" href="#" class="btn btn-danger btn-sm" id="btn_cancelar_reglote"><i class="fas fa-trash mr-2"></i>Anular Registro</a>
                                    <a style="display:none;" href="#" class="btn btn-primary btn-sm" id="btn_reglote"><i class="fas fa-save mr-2"></i> Registrar Lote</a>
                                </div>                      
                            </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover">
                        <thead >
                          <tr class="text-center text-white" style="font-size:12px">
                              
                              <th colspan="6" class="bg-white"></th>
                              <th colspan="2" width="50" class="bg-rojo">Precios Venta</th>
                              </thead>                                   
                          </tr>        
                        <thead class="thead-dark">
                           
                            <tr class="text-center" style="font-size:12px">
                                <th width="40px" >Id.</th>
                                <th width="350px">Descripción</th>
                                <th width="40">Stock</th>
                                <th width="100">F. Vencimiento</th>
                                <th width="80">Cantidad</th>                                
                                <th class="bg-verde">P./compra</th>
                                <th width="50" class="bg-azul">P./Cen.</th>
                                <th width="50" class="bg-azul">P./Suc.</th>
                                <th width="50">TOTAL</th>
                                <th>Sel.</th>
                            </tr>
                            <tr>
                                <td width="40px" style="font-size:12px" class="align-middle" id="txt_cod_medicamento" name="txt_cod_medicamento"></td>                               
                                <td width="350px" style="font-size:12px" class="align-middle" id="txt_descripcion-reglote" name="txt_descripcion-reglote">-</td>
                                <td width="40" style="font-size:14px" class="text-center align-middle" id="stock-reglote" name="stock-reglote">0</td>                                
                                <td width="100"><input class="form-control form-control-sm" type="date"  name ="txt_fecvencimiento" id="txt_fecvencimiento"></td>
                                <td width="80"><input class="form-control form-control-sm" type="number"  name="txt_cant_producto-reglote" id="txt_cant_producto-reglote" disabled></td>
                                <td width="80"><input class="form-control form-control-sm" type="number"  name="txt_precio-reglote" id="txt_precio-reglote" disabled></td>
                                <td width="80"><input class="form-control form-control-sm" type="number"  name="txt_precio-cen" id="txt_precio-cen" disabled></td>
                                <td width="80"><input class="form-control form-control-sm" type="number"  name="txt_precio-suc" id="txt_precio-suc" disabled></td>
                                <td name="txt_precio_total-reglote" id="txt_precio_total-reglote" style="font-size:14px" class="text-center align-middle">0.00</td>
                                <td class="text-center"><a href="#" name="add_product_venta1" id="add_product_venta1" class="btn btn-outline-success btn-sm" style="display: none;"><i class="fas fa-arrow-right mr-2"></i></a></td>
                            </tr>
                            <tr class="text-center align-middle" style="font-size:12px">
                                <th width="40px">Id.</th>                               
                                <th width="350px" >Descripción</th>
                                <th width="40px">Cantidad</th>
                                <th width="100px">F. Ven.</th>
                                <th class="bg-verde" width="100px" >Precio Compra</th>
                                <th class="bg-azul" width="100px" >P. Venta Cen.</th>
                                <th class="bg-azul" width="100px" >P. Venta Suc.</th>
                                <th width="100px" >Sub Total</th>
                                <th width="100px"  colspan="2" >Eliminar</th>
                            </tr>
                        </thead>
                        <tbody id="detalle_venta1">
                            <!-- Contenido ajax -->

                        </tbody>

                        <tfoot id="detalle_totales1">
                            <!-- Contenido ajax -->
                        </tfoot>
                    </table>

                </div>


            </div>


        </div>           
                
               
    </div>

<!-- Modal Buscar Medicamento registro lote serachMedLote laboratorio BAGO -->
<div class="modal fade" id="modsearreglote" name="modsearreglote" tabindex="-1" aria-labelledby="mod-select-med-reglote" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-lila text-white">
        <h5 class="h6 modal-title" id="exampleModalLabel">Agregar Medicamento <strong>BAGÓ</strong></h5>       
      </div>
      <div class="modal-body">
      <div class="col-12 m-auto">
        <div class="table-responsive">
			<table class="table table-striped table-sm table-bordered" id="table">               
				<thead class="thead-dark">
					<tr class="text-center">
						<th style="font-size:12px" width="30px">ID</th>											
                        <th style="font-size:12px" width="400px">DESCRIPCION</th>
                        <th style="font-size:12px" width="50px">STOCK</th>
                        <th style="font-size:12px" width="50px">PRECIO</th>                        
						<th style="font-size:12px" width="30px" > Sel.</th>
				</thead>
				<tbody>

                    
					<?php
                    
                    
					include "../conexion.php";
					$query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
															pa.medicamento AS despactivo, m.preciounitario, m.laboratorio,
															f.nombre AS desforma,
															ate.nombre AS desaterapeutica,
															un.abreviatura AS desunidad,
															u.descripcion AS desuso 
															FROM medicamento m
															INNER JOIN pactivo pa ON m.idpactivo = pa.id
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
															INNER JOIN unidades un ON m.idunidad = un.idunidad
															INNER JOIN uso u ON pa.uso = u.id
                                                            WHERE laboratorio = 3
															ORDER BY m.nombre ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' / '. $data['despactivo'] .' / ' . $data['desforma'].' / ' . $data['concentracion'].'  ' . $data['desunidad'].' / ' . $data['desaterapeutica'];
                            ?>
							<tr id="<?php echo $data['idmedicamento']; ?>" >
								<td class="text-center align-middle"><?php echo $data['idmedicamento']; ?></td>								
                                <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'] ;?> 
                                <td class="align-middle" style="font-size:14px"><?php echo $data['stock'] ;?>   
                                <td class="align-middle" style="font-size:14px"><?php echo $data['preciounitario'] ;?>                                
                                </td>								
									<?php if ($_SESSION['rol'] == 1) { ?>
								<td class="text-center">  
                                    <button type="button" class="btn btn-lila btn-sm btn-accion"
                                            idp="<?php echo $data['idmedicamento'];?>" 
                                            idmodal="#modsearreglote"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['stock'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            ><i class='fas fa-check'></i>
                                        </button>   
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

<!-- Modal Buscar Medicamento registro lote serachMedLote laboratorio ALCOS -->
<div class="modal fade" id="modsearreglotealcos" name="modsearreglotealcos" tabindex="-1" aria-labelledby="mod-select-med-reglote" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="h6 modal-title" id="exampleModalLabel">  Agregar Medicamento <strong> ALCOS</strong></h5>       
      </div>
      <div class="modal-body">
      <div class="col-12 m-auto">
        <div class="table-responsive">
			<table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
					<tr class="text-center">
						<th style="font-size:12px" width="30px">ID</th>
						<th style="font-size:12px" width="50px">COD.</th>						
                        <th style="font-size:12px" width="400px">DESCRIPCION</th>                       
						<th style="font-size:12px" width="30px" > Sel.</th>
				</thead>
				<tbody>

                    
					<?php
                    
                    
					include "../conexion.php";
					$query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
															pa.medicamento AS despactivo, m.preciounitario, m.laboratorio,
															f.nombre AS desforma,
															ate.nombre AS desaterapeutica,
															un.abreviatura AS desunidad,
															u.descripcion AS desuso 
															FROM medicamento m
															INNER JOIN pactivo pa ON m.idpactivo = pa.id
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
															INNER JOIN unidades un ON m.idunidad = un.idunidad
															INNER JOIN uso u ON pa.uso = u.id
                                                            WHERE laboratorio = 1
															ORDER BY m.nombre ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' / '. $data['despactivo'] .' / ' . $data['desforma'].' / ' . $data['concentracion'].'  ' . $data['desunidad'].' / ' . $data['desaterapeutica'];
                            ?>
							<tr id="<?php echo $data['idmedicamento']; ?>" >
								<td class="text-center align-middle"><?php echo $data['idmedicamento']; ?></td>
								<td style="font-size:14px " class="text-center align-middle"><?php echo $data['codmedicamento']; ?></td>								
                                <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'] ;?>                                
                                </td>								
									<?php if ($_SESSION['rol'] == 1) { ?>
								<td class="text-center">                                    
                                    <button type="button" class="btn btn-success btn-sm btn-accion"
                                            idp="<?php echo $data['idmedicamento'];?>" 
                                            idmodal="#modsearreglotealcos"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['stock'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            ><i class='fas fa-check'></i>
                                        </button>   
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
<!-- Modal Buscar Medicamento registro lote serachMedLote laboratorio VITA -->
<div class="modal fade" id="modsearreglotevita" name="modsearreglotevita" tabindex="-1" aria-labelledby="mod-select-med-reglote" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-orange text-white">
        <h5 class="h6 modal-title" id="exampleModalLabel">Agregar Medicamento  <strong>VITA</strong></h5>       
      </div>
      <div class="modal-body">
      <div class="col-12 m-auto">
        <div class="table-responsive">
			<table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
					<tr class="text-center">
						<th style="font-size:12px" width="30px">ID</th>
						<th style="font-size:12px" width="50px">COD.</th>						
                        <th style="font-size:12px" width="400px">DESCRIPCION</th>                       
						<th style="font-size:12px" width="30px" > Sel.</th>
				</thead>
				<tbody>

                    
					<?php
                    
                    
					include "../conexion.php";
					$query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
															pa.medicamento AS despactivo, m.preciounitario, m.laboratorio,
															f.nombre AS desforma,
															ate.nombre AS desaterapeutica,
															un.abreviatura AS desunidad,
															u.descripcion AS desuso 
															FROM medicamento m
															INNER JOIN pactivo pa ON m.idpactivo = pa.id
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
															INNER JOIN unidades un ON m.idunidad = un.idunidad
															INNER JOIN uso u ON pa.uso = u.id
                                                            WHERE laboratorio = 2
															ORDER BY m.nombre ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' / '. $data['despactivo'] .' / ' . $data['desforma'].' / ' . $data['concentracion'].'  ' . $data['desunidad'].' / ' . $data['desaterapeutica'];
                            ?>
							<tr id="<?php echo $data['idmedicamento']; ?>" >
								<td class="text-center align-middle"><?php echo $data['idmedicamento']; ?></td>
								<td style="font-size:14px " class="text-center align-middle"><?php echo $data['codmedicamento']; ?></td>								
                                <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'] ;?>                                
                                </td>								
									<?php if ($_SESSION['rol'] == 1) { ?>
								<td class="text-center">                                    
                                <button type="button" class="btn btn-success btn-sm btn-accion"
                                            idp="<?php echo $data['idmedicamento'];?>" 
                                            idmodal="#modsearreglotevita"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['stock'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            ><i class='fas fa-check'></i>
                                        </button>   
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
<!-- Modal Buscar Medicamento registro lote serachMedLote laboratorio COFAR -->
<div class="modal fade" id="modsearreglotecofar" name="modsearreglotecofar" tabindex="-1" aria-labelledby="mod-select-med-reglote" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header text-lila">
        <h5 class="h6 modal-title" id="exampleModalLabel">Agregar Medicamento <strong>COFAR</strong></h5>       
      </div>
      <div class="modal-body">
      <div class="col-12 m-auto">
        <div class="table-responsive">
			<table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
					<tr class="text-center">
						<th style="font-size:12px" width="30px">ID</th>
						<th style="font-size:12px" width="50px">COD.</th>						
                        <th style="font-size:12px" width="400px">DESCRIPCION</th>                       
						<th style="font-size:12px" width="30px" > Sel.</th>
				</thead>
				<tbody>

                    
					<?php
                    
                    
					include "../conexion.php";
					$query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
															pa.medicamento AS despactivo, m.preciounitario, m.laboratorio,
															f.nombre AS desforma,
															ate.nombre AS desaterapeutica,
															un.abreviatura AS desunidad,
															u.descripcion AS desuso 
															FROM medicamento m
															INNER JOIN pactivo pa ON m.idpactivo = pa.id
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
															INNER JOIN unidades un ON m.idunidad = un.idunidad
															INNER JOIN uso u ON pa.uso = u.id
                                                            WHERE laboratorio = 4
															ORDER BY m.nombre ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' / '. $data['despactivo'] .' / ' . $data['desforma'].' / ' . $data['concentracion'].'  ' . $data['desunidad'].' / ' . $data['desaterapeutica'];
                            ?>
							<tr id="<?php echo $data['idmedicamento']; ?>" >
								<td class="text-center align-middle"><?php echo $data['idmedicamento']; ?></td>
								<td style="font-size:14px " class="text-center align-middle"><?php echo $data['codmedicamento']; ?></td>								
                                <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'] ;?>                                
                                </td>								
									<?php if ($_SESSION['rol'] == 1) { ?>
								<td class="text-center">                                    
                                <button type="button" class="btn btn-success btn-sm btn-accion"
                                            idp="<?php echo $data['idmedicamento'];?>" 
                                            idmodal="#modsearreglotecofar"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['stock'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            ><i class='fas fa-check'></i>
                                        </button>   
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
<!-- Modal Buscar Medicamento registro lote serachMedLote laboratorio IFA -->
<div class="modal fade" id="modsearregloteifa" name="modsearregloteifa" tabindex="-1" aria-labelledby="mod-select-med-reglote" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-rojo text-white">
        <h5 class="h6 modal-title" id="exampleModalLabel">Agregar Medicamento <strong>IFA</strong></h5>       
      </div>
      <div class="modal-body">
      <div class="col-12 m-auto">
        <div class="table-responsive">
			<table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
					<tr class="text-center">
						<th style="font-size:12px" width="30px">ID</th>
						<th style="font-size:12px" width="50px">COD.</th>						
                        <th style="font-size:12px" width="400px">DESCRIPCION</th>                       
						<th style="font-size:12px" width="30px" > Sel.</th>
				</thead>
				<tbody>

                    
					<?php
                    
                    
					include "../conexion.php";
					$query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
															pa.medicamento AS despactivo, m.preciounitario, m.laboratorio,
															f.nombre AS desforma,
															ate.nombre AS desaterapeutica,
															un.abreviatura AS desunidad,
															u.descripcion AS desuso 
															FROM medicamento m
															INNER JOIN pactivo pa ON m.idpactivo = pa.id
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
															INNER JOIN unidades un ON m.idunidad = un.idunidad
															INNER JOIN uso u ON pa.uso = u.id
                                                            WHERE laboratorio = 5
															ORDER BY m.nombre ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' / '. $data['despactivo'] .' / ' . $data['desforma'].' / ' . $data['concentracion'].'  ' . $data['desunidad'].' / ' . $data['desaterapeutica'];
                            ?>
							<tr id="<?php echo $data['idmedicamento']; ?>" >
								<td class="text-center align-middle"><?php echo $data['idmedicamento']; ?></td>
								<td style="font-size:14px " class="text-center align-middle"><?php echo $data['codmedicamento']; ?></td>								
                                <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'] ;?>                                
                                </td>								
									<?php if ($_SESSION['rol'] == 1) { ?>
								<td class="text-center">                                    
                                <button type="button" class="btn btn-success btn-sm btn-accion"
                                            idp="<?php echo $data['idmedicamento'];?>" 
                                            idmodal="#modsearregloteifa"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['stock'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            ><i class='fas fa-check'></i>
                                        </button>   
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
<!-- Modal Buscar Medicamento registro lote serachMedLote laboratorio PHARMA -->
<div class="modal fade" id="modsearreglotepharma" name="modsearreglotepharma" tabindex="-1" aria-labelledby="mod-select-med-reglote" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-celeste text-white">
        <h5 class="h6 modal-title" id="exampleModalLabel">Agregar Medicamento <strong> PHARMA</strong></h5>       
      </div>
      <div class="modal-body">
      <div class="col-12 m-auto">
        <div class="table-responsive">
			<table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
					<tr class="text-center">
						<th style="font-size:12px" width="30px">ID</th>
						<th style="font-size:12px" width="50px">COD.</th>						
                        <th style="font-size:12px" width="400px">DESCRIPCION</th>                       
						<th style="font-size:12px" width="30px" > Sel.</th>
				</thead>
				<tbody>

                    
					<?php
                    
                    
					include "../conexion.php";
					$query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
															pa.medicamento AS despactivo, m.preciounitario, m.laboratorio,
															f.nombre AS desforma,
															ate.nombre AS desaterapeutica,
															un.abreviatura AS desunidad,
															u.descripcion AS desuso 
															FROM medicamento m
															INNER JOIN pactivo pa ON m.idpactivo = pa.id
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
															INNER JOIN unidades un ON m.idunidad = un.idunidad
															INNER JOIN uso u ON pa.uso = u.id
                                                            WHERE laboratorio = 6
															ORDER BY m.nombre ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' / '. $data['despactivo'] .' / ' . $data['desforma'].' / ' . $data['concentracion'].'  ' . $data['desunidad'].' / ' . $data['desaterapeutica'];
                            ?>
							<tr id="<?php echo $data['idmedicamento']; ?>" >
								<td class="text-center align-middle"><?php echo $data['idmedicamento']; ?></td>
								<td style="font-size:14px " class="text-center align-middle"><?php echo $data['codmedicamento']; ?></td>								
                                <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'] ;?>                                
                                </td>								
									<?php if ($_SESSION['rol'] == 1) { ?>
								<td class="text-center">                                    
                                <button type="button" class="btn btn-success btn-sm btn-accion"
                                            idp="<?php echo $data['idmedicamento'];?>" 
                                            idmodal="#modsearreglotepharma"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['stock'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            ><i class='fas fa-check'></i>
                                        </button>   
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
<!-- Modal Buscar Medicamento registro lote serachMedLote laboratorio TECNOFARMA -->
<div class="modal fade" id="modsearreglotetecnofarma" name="modsearreglotetecnofarma" tabindex="-1" aria-labelledby="mod-select-med-reglote" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-rojo text-white">
        <h5 class="h6 modal-title" id="exampleModalLabel">Agregar Medicamento <strong>TECNOFARMA</strong></h5>       
      </div>
      <div class="modal-body">
      <div class="col-12 m-auto">
        <div class="table-responsive">
			<table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
					<tr class="text-center">
						<th style="font-size:12px" width="30px">ID</th>
						<th style="font-size:12px" width="50px">COD.</th>						
                        <th style="font-size:12px" width="400px">DESCRIPCION</th>                       
						<th style="font-size:12px" width="30px" > Sel.</th>
				</thead>
				<tbody>

                    
					<?php
                    
                    
					include "../conexion.php";
					$query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
															pa.medicamento AS despactivo, m.preciounitario, m.laboratorio,
															f.nombre AS desforma,
															ate.nombre AS desaterapeutica,
															un.abreviatura AS desunidad,
															u.descripcion AS desuso 
															FROM medicamento m
															INNER JOIN pactivo pa ON m.idpactivo = pa.id
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
															INNER JOIN unidades un ON m.idunidad = un.idunidad
															INNER JOIN uso u ON pa.uso = u.id
                                                            WHERE laboratorio = 7
															ORDER BY m.nombre ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' / '. $data['despactivo'] .' / ' . $data['desforma'].' / ' . $data['concentracion'].'  ' . $data['desunidad'].' / ' . $data['desaterapeutica'];
                            ?>
							<tr id="<?php echo $data['idmedicamento']; ?>" >
								<td class="text-center align-middle"><?php echo $data['idmedicamento']; ?></td>
								<td style="font-size:14px " class="text-center align-middle"><?php echo $data['codmedicamento']; ?></td>								
                                <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'] ;?>                                
                                </td>								
									<?php if ($_SESSION['rol'] == 1) { ?>
								<td class="text-center">                                    
                                <button type="button" class="btn btn-success btn-sm btn-accion"
                                            idp="<?php echo $data['idmedicamento'];?>" 
                                            idmodal="#modsearreglotetecnofarma"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['stock'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            ><i class='fas fa-check'></i>
                                        </button>   
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
<!-- Modal Buscar Medicamento registro lote serachMedLote laboratorio FARMAVAL -->
<div class="modal fade" id="modsearreglotefarmaval" name="modsearreglotefarmaval" tabindex="-1" aria-labelledby="mod-select-med-reglote" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-celeste text-white">
        <h5 class="h6 modal-title" id="exampleModalLabel">Agregar Medicamento <strong>FARMAVAL</strong></h5>       
      </div>
      <div class="modal-body">
      <div class="col-12 m-auto">
        <div class="table-responsive">
			<table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
					<tr class="text-center">
						<th style="font-size:12px" width="30px">ID</th>
						<th style="font-size:12px" width="50px">COD.</th>						
                        <th style="font-size:12px" width="400px">DESCRIPCION</th>                       
						<th style="font-size:12px" width="30px" > Sel.</th>
				</thead>
				<tbody>

                    
					<?php
                    
                    
					include "../conexion.php";
					$query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
															pa.medicamento AS despactivo, m.preciounitario, m.laboratorio,
															f.nombre AS desforma,
															ate.nombre AS desaterapeutica,
															un.abreviatura AS desunidad,
															u.descripcion AS desuso 
															FROM medicamento m
															INNER JOIN pactivo pa ON m.idpactivo = pa.id
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
															INNER JOIN unidades un ON m.idunidad = un.idunidad
															INNER JOIN uso u ON pa.uso = u.id
                                                            WHERE laboratorio = 8
															ORDER BY m.nombre ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' / '. $data['despactivo'] .' / ' . $data['desforma'].' / ' . $data['concentracion'].'  ' . $data['desunidad'].' / ' . $data['desaterapeutica'];
                            ?>
							<tr id="<?php echo $data['idmedicamento']; ?>" >
								<td class="text-center align-middle"><?php echo $data['idmedicamento']; ?></td>
								<td style="font-size:14px " class="text-center align-middle"><?php echo $data['codmedicamento']; ?></td>								
                                <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'] ;?>                                
                                </td>								
									<?php if ($_SESSION['rol'] == 1) { ?>
								<td class="text-center">                                    
                                <button type="button" class="btn btn-success btn-sm btn-accion"
                                            idp="<?php echo $data['idmedicamento'];?>" 
                                            idmodal="#modsearreglotefarmaval"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['stock'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            ><i class='fas fa-check'></i>
                                        </button>   
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
<!-- Modal Buscar Medicamento registro lote serachMedLote laboratorio SAE -->
<div class="modal fade" id="modsearreglotesae" name="modsearreglotesae" tabindex="-1" aria-labelledby="mod-select-med-reglote" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning text-azul">
        <h5 class="h6 modal-title" id="exampleModalLabel">Agregar Medicamento <strong>SAE</strong></h5>       
      </div>
      <div class="modal-body">
      <div class="col-12 m-auto">
        <div class="table-responsive">
			<table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
					<tr class="text-center">
						<th style="font-size:12px" width="30px">ID</th>
						<th style="font-size:12px" width="50px">COD.</th>						
                        <th style="font-size:12px" width="400px">DESCRIPCION</th>                       
						<th style="font-size:12px" width="30px" > Sel.</th>
				</thead>
				<tbody>

                    
					<?php
                    
                    
					include "../conexion.php";
					$query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
															pa.medicamento AS despactivo, m.preciounitario, m.laboratorio,
															f.nombre AS desforma,
															ate.nombre AS desaterapeutica,
															un.abreviatura AS desunidad,
															u.descripcion AS desuso 
															FROM medicamento m
															INNER JOIN pactivo pa ON m.idpactivo = pa.id
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
															INNER JOIN unidades un ON m.idunidad = un.idunidad
															INNER JOIN uso u ON pa.uso = u.id
                                                            WHERE laboratorio = 9
															ORDER BY m.nombre ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' / '. $data['despactivo'] .' / ' . $data['desforma'].' / ' . $data['concentracion'].'  ' . $data['desunidad'].' / ' . $data['desaterapeutica'];
                            ?>
							<tr id="<?php echo $data['idmedicamento']; ?>" >
								<td class="text-center align-middle"><?php echo $data['idmedicamento']; ?></td>
								<td style="font-size:14px " class="text-center align-middle"><?php echo $data['codmedicamento']; ?></td>								
                                <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'] ;?>                                
                                </td>								
									<?php if ($_SESSION['rol'] == 1) { ?>
								<td class="text-center">                                    
                                <button type="button" class="btn btn-success btn-sm btn-accion"
                                            idp="<?php echo $data['idmedicamento'];?>" 
                                            idmodal="#modsearreglotesae"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['stock'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            ><i class='fas fa-check'></i>
                                        </button>   
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
<!-- Modal Buscar Medicamento registro lote serachMedLote laboratorio INTI -->
<div class="modal fade" id="modsearregloteinti" name="modsearregloteinti" tabindex="-1" aria-labelledby="mod-select-med-reglote" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-rojo text-white">
        <h5 class="h6 modal-title" id="exampleModalLabel">Agregar Medicamento <strong>INTI</strong></h5>       
      </div>
      <div class="modal-body">
      <div class="col-12 m-auto">
        <div class="table-responsive">
			<table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
					<tr class="text-center">
						<th style="font-size:12px" width="30px">ID</th>
						<th style="font-size:12px" width="50px">COD.</th>						
                        <th style="font-size:12px" width="400px">DESCRIPCION</th>                       
						<th style="font-size:12px" width="30px" > Sel.</th>
				</thead>
				<tbody>

                    
					<?php
                    
                    
					include "../conexion.php";
					$query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
															pa.medicamento AS despactivo, m.preciounitario, m.laboratorio,
															f.nombre AS desforma,
															ate.nombre AS desaterapeutica,
															un.abreviatura AS desunidad,
															u.descripcion AS desuso 
															FROM medicamento m
															INNER JOIN pactivo pa ON m.idpactivo = pa.id
															INNER JOIN forma f ON m.idforma = f.idforma
															INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
															INNER JOIN unidades un ON m.idunidad = un.idunidad
															INNER JOIN uso u ON pa.uso = u.id
                                                            WHERE laboratorio = 10
															ORDER BY m.nombre ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' / '. $data['despactivo'] .' / ' . $data['desforma'].' / ' . $data['concentracion'].'  ' . $data['desunidad'].' / ' . $data['desaterapeutica'];
                            ?>
							<tr id="<?php echo $data['idmedicamento']; ?>" >
								<td class="text-center align-middle"><?php echo $data['idmedicamento']; ?></td>
								<td style="font-size:14px " class="text-center align-middle"><?php echo $data['codmedicamento']; ?></td>								
                                <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'] ;?>                                
                                </td>								
									<?php if ($_SESSION['rol'] == 1) { ?>
								<td class="text-center">                                    
                                <button type="button" class="btn btn-success btn-sm btn-accion"
                                            idp="<?php echo $data['idmedicamento'];?>" 
                                            idmodal="#modsearregloteinti"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['stock'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            ><i class='fas fa-check'></i>
                                        </button>   
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

</div>

</div>
<!-- /.container-fluid -->
</div>  
<!-- End of Main Content -->


<?php include_once "includes/footer.php"; ?>
 