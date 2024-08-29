<?php
include "includes/header.php";
include "../conexion.php";

// MOSTRAR LOS DATOS DEL MEDICAMENTO

if (empty($_REQUEST['id'])) {
  header("Location: lista_medicamentos.php");
  mysqli_close($conexion);
}
$idmedicamento = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
                                      m.preciounitario, m.cantidadminima, m.formula, m.indicaciones, m.posologia,
                                      m.contradicciones, m.presentacion,
                                      pa.medicamento AS despactivo,
                                      f.nombre AS desforma,
                                      ate.nombre AS desaterapeutica,
                                      un.abreviatura AS desunidad,
                                      u.descripcion AS desuso, 
                                      p.proveedor AS deslab
                                      FROM medicamento m
                                      INNER JOIN pactivo pa ON m.idpactivo = pa.id
                                      INNER JOIN forma f ON m.idforma = f.idforma
                                      INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
                                      INNER JOIN unidades un ON m.idunidad = un.idunidad
                                      INNER JOIN uso u ON pa.uso = u.id 
                                      INNER JOIN proveedor p ON m.laboratorio = p.codproveedor
                                      WHERE idmedicamento = $idmedicamento");
mysqli_close($conexion);
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_medicamentos.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $idmedicamento = $data['idmedicamento'];
    $lab = $data['deslab'];
    $nombrecomercial = $data['nombre'];
    $codmedicamento = $data['codmedicamento'];
    $preciounitario = $data['preciounitario'];
    $cantidadminima = $data['cantidadminima'];
    $presentacion = $data['presentacion'];
    $despactivo = $data['despactivo'];
    $desforma = $data['desforma'];
    $desaterapeutica = $data['desaterapeutica'];
    $concentracion = $data['concentracion'] .' '. $data['desunidad'];
    $desuso = $data['desuso'];
    if ($desuso != 'LIBRE'){
      $claseuso = 'h5 text-danger ';
    }
    else{
      $claseuso = 'h5 text-verde ';
    };

    $formula = $data['formula'];
    $indicaciones = $data['indicaciones'];
    $posologia = $data['posologia'];
    $contradicciones = $data['contradicciones'];
    
  }
}

?>
<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">
	<!-- Page Heading -->	
<!-- 	<nav aria-label="breadcrumb">  	
    <ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	
      <li class="breadcrumb-item"><a href="lista_medicamentos.php"><i class="fas fa-tablets text-info"></i></a></li>
      <li class="breadcrumb-item"><i class="fas fa-pills text-info mr-2"></i><i class="fas fa-file text-info"></i></li>			
    	<li class="breadcrumb-item active" aria-current="page">Detalle Medicamento</li>
  	</ol>
  </nav> -->

  <!-- carta general -->
  <div class="card">
     <!-- Titulo  y encabezado del detalle --> 
    <div class="card-header bg-gris text-white ">
      <div class="d-sm-flex align-items-center justify-content-between mb-1">
        <h1 class="h5 mb-0 text-uppercase text-azul">DETALLE: <span class="h5 font-weight-bold text-azul ml-4"><?php echo $nombrecomercial; ?> </span></h1>        		
        <a href="lista_medicamentos.php" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left mr-1"></i>Regresar</a>				
      </div>
    </div>
    <!-- Final Titulo  y encabezado del detalle --> 
    <!-- Cuerpo del detalle -->
    <div class="card-body">
      <!-- FILA PRINCIPAL -->  
      <div class="row">
        <div class="col-md-12">
          <!-- PRIMERA FILA -->  
          <div class="row mb-4">
            <!-- PRIMER CUADRANTE -->  
            <div class="col-md-6">
              <div class="row">
                <!-- IMAGEN  -->  
                <div class="col-md-4">
                  <img src="img/comp.jpg" class="img-thumbnail mb-2"/>
                  <label hidden class="h5 text-info" id="idm" name="idm"><?php echo $idmedicamento; ?></label>
                  <label class="h5 text-celeste"><?php echo $codmedicamento; ?></label>
                  <label class="h5 text-celeste" style="font-weight: bold;"><span class="h6 text-dark">Precio U/ Bs. </span><?php echo $preciounitario; ?></label>
                  <label class="h5 text-rojo" style="font-weight: bold;" ><span class="h6 text-dark">Cant. Minima - </span><?php echo $cantidadminima; ?></label>
                  <label class="<?php echo $claseuso;?>" style="font-weight: bold;" ><span class="h6 text-dark">Uso:  </span><?php echo $desuso; ?></label>
                </div>
                <!-- DATOS BÁSICOS -->  
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-6">
                      <p class="mb-2" >Laboratorio:</p></div> 
                    <div class="col-6">
                      <p class="mb-2" ><span class="h6 text-azul" style="font-weight: bold;"> <?php echo $lab; ?></span> </p></div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <p class="mb-2" >Presentación:</p></div> 
                    <div class="col-6">
                      <p class="mb-2" ><span class="h6 text-azul" style="font-weight: bold;"> <?php echo $presentacion; ?></span> </p></div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <p class="mb-2" >Principio Activo:</p></div> 
                    <div class="col-6">
                      <p class="mb-2" ><span class="h6 text-azul" style="font-weight: bold;"> <?php echo $despactivo; ?></span> </p></div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <p class="mb-2" >Forma Farmacéutica:</p></div> 
                    <div class="col-6">
                      <p class="mb-2" ><span class="h6 text-azul" style="font-weight: bold;"><?php echo $desforma; ?></span></p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <p class="mb-2" >Accion Terapéutica:</p></div> 
                    <div class="col-6">
                      <p class="mb-2" ><span class="h6 text-azul" style="font-weight: bold;"><?php echo $desaterapeutica; ?></span></p></div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <p class="mb-2" >Concentración:</p> 
                    </div> 
                    <div class="col-6">  
                      <p class="mb-2" ><span class="h6 text-azul" style="font-weight: bold;"><?php echo $concentracion; ?></span></p>
                    </div>
                  </div>                 
                </div>
              </div>
            </div>
            <!-- FINAL PRIMER CUADRANTE -->  

            <!-- SEGUNDO CUADRANTE -->  
            <div class="col-md-6">
              <!-- CARTAS DESCRIPCION -->  
              <div id="card-descripcion">
                <!-- FORMULA -->
                <div class="card">
                  <div class="card-header">
                    <a class="card-link" data-toggle="collapse" data-parent="#card-descripcion" href="#card-formula">FÓRMULA</a>
                  </div>
                  <div id="card-formula" class="collapse show">
                    <div class="card-body">
                    <?php echo $formula; ?>
                    </div>
                  </div>
                </div>
                 <!-- INDICACIONES -->
                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" data-parent="#card-descripcion" href="#card-indicaciones">INDICACIONES</a>
                  </div>
                  <div id="card-indicaciones" class="collapse">
                    <div class="card-body">
                    <?php echo $indicaciones; ?>
                    </div>
                  </div>
                </div>
                <!-- POSOLOGÍA -->
                <div class="card">
                  <div class="card-header">
                    <a class="card-link" data-toggle="collapse" data-parent="#card-descripcion" href="#card-posologia">POSOLOGÍA</a>
                  </div>
                  <div id="card-posologia" class="collapse">
                    <div class="card-body">
                    <?php echo $posologia; ?>
                    </div>
                  </div>
                </div>
                 <!-- CONTRADICCIONES -->
                <div class="card">
                  <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" data-parent="#card-descripcion" href="#card-contradicciones">CONTRADICCIONES</a>
                  </div>
                  <div id="card-contradicciones" class="collapse">
                    <div class="card-body">
                      <?php echo $contradicciones; ?>
                    </div>
                  </div>
                </div>
              </div>
              <!-- FINAL CARTAS DESCRIPCION -->
            </div>
            <!-- FINAL SEGUNDO CUADRANTE -->
          </div>
          <!-- FINAL PRIMERA FILA -->

          <!-- SEGUNDA FILA -->    
          <div class="row">
            <!-- TERCER CUADRANTE -->  
            <div class="col-md-7">
              <!-- TABLAS MEDICAMENTO --> 
              <div class="tabbable" id="tabs-idmedicamento">
                <!-- tabs - navegacion -->
                <ul class="nav nav-tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#tab1" data-toggle="tab"> Ingresos</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#tab2" data-toggle="tab"> Ventas</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#tab3" data-toggle="tab"> Stock</a>
                  </li>                 
                </ul>
                <!-- tabs - contenido -->
                <div class="tab-content">
                  <!-- tabs - primer elemento INGRESOS -->
                  <div class="tab-pane active" id="tab1">
                    <div class="card">                                           
                      <div class="card-body">
                        <div class="table-responsive mb-4">
                          <table class="table table-striped table-sm table-bordered" id="table">
                            <thead class="thead-dark">
                              <tr class="text-center">
                                <th>ID LOTE</th>
                                <th>F. VEN</th>
                                <th>CANT.</th>	
                              </tr>
                            </thead>
                            <tbody> 
                              <?php
                              include "../conexion.php";

                              $query = mysqli_query($conexion, "SELECT nolote, fecvencimiento, cantidad  FROM detallelote where codmedicamento = $idmedicamento");
                              $result = mysqli_num_rows($query);
                              if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { 
                              ?>
                                <tr>
                                  <td class="text-center"><?php echo $data['nolote']; ?></td>
                                  <td><?php echo $data['fecvencimiento']; ?></td>																
                                  <td class="text-center"><?php echo $data['cantidad']; ?></td>											  
                                </tr>
                                  <?php }
                                } ?>
                            </tbody>
                          </table>   
                        </div>
                        <?php
                              include "../conexion.php";

                              $querysum = mysqli_query($conexion, "SELECT SUM(cantidad) AS cantidad FROM detallelote where codmedicamento = $idmedicamento");
                              $datasum = mysqli_fetch_assoc($querysum)
                        ?>    
                          <label class="h5 text-verde font-weight-bold"><span class="h6 text-azul mr-2">TOTAL INGRESOS: </span><?php echo $datasum['cantidad']; ?><span class="h6 text-azul ml-2"> Unidades. </span></label>  
                      </div>
                    </div>
                  </div>  
                  <!-- tabs - FINAL primer elemento INGRESOS -->

                   <!-- tabs - segundo elemento VENTAS -->
                  <div class="tab-pane" id="tab2">
                    <div class="card">                                           
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-striped table-sm table-bordered" >
                            <thead class="thead-dark">
                              <tr class="text-center">
                                <th>NOTA VENTA.</th>
                                <th>SUCURSAL</th>
                                <th>F. VENCIMIENTO</th>
                                <th>CANT.</th>	
                              </tr>
                            </thead>
                            <tbody>
                            <?php
                              include "../conexion.php";

                              $queryventa = mysqli_query($conexion, "SELECT df.nofactura, df.fechavencimiento, df.cantidad,
                                                                    f.usuario, u.idsucursal, s.nombre
                                                                    FROM detallefacturas df
                                                                    INNER JOIN facturas f ON df.nofactura = f.nofactura
                                                                    INNER JOIN usuario u ON f.usuario = u.idusuario
                                                                    INNER JOIN sucursal s ON u.idsucursal = s.idsucursal
                                                                    WHERE codmedicamento = $idmedicamento");
                              $result = mysqli_num_rows($queryventa);
                              if ($result > 0) {
                                while ($dataventa = mysqli_fetch_assoc($queryventa)) { ?>
                              <tr>
                                <td class="text-center"><?php echo $dataventa['nofactura']; ?></td>
                                <td><?php echo $dataventa['nombre']; ?></td>		
                                <td class="text-center"><?php echo $dataventa['fechavencimiento']; ?></td>																
                                <td class="text-center"><?php echo $dataventa['cantidad']; ?></td>											  
                              </tr>
                              <?php }
                                } ?>
                            </tbody>
                          </table>                    
                        </div> 
                            <?php
                                  include "../conexion.php";
                                  $querycanventa = mysqli_query($conexion, "SELECT SUM(cantidad) AS cantidad FROM detallefacturas where codmedicamento = $idmedicamento");
                                  $datacanventa = mysqli_fetch_assoc($querycanventa);

                                  $querysumventa = mysqli_query($conexion, "SELECT cantidad, precio_venta FROM detallefacturas where codmedicamento = $idmedicamento");
                                  $resultsumventa = mysqli_num_rows($querysumventa);
                                  if ($resultsumventa > 0) {
                                    $suma = 0;
                                    while ($datasumventa = mysqli_fetch_assoc($querysumventa)) { 
                                   
                                    $cantidad = $datasumventa['cantidad'];
                                    $precio = $datasumventa['precio_venta'];
                                    $suma = ($cantidad * $precio)+  $suma;                                  

                             }
                                } else { $suma = 0;}?>                                
                                    <label class="h5 text-azul font-weight-bold"><span class="h6 text-dark mr-2">TOTAL VENTAS:</span><?php echo $datacanventa['cantidad']; ?><span class="h6 text-azul ml-2">  Unidades Vendidas</span></label> <br>
                                    <label class="h5 text-verde font-weight-bold"><span class="h6 text-dark mr-2">IMPORTE POR VENTAS:</span> Bs. <?php echo $suma ?>,00<span class="h6 text-dark"></span></label>
                      
                    </div>
                    </div>
                  </div>
                  <!-- tabs - FINAL segundo elemento VENTAS -->

                   <!-- tabs - tercer elemento STOCK -->
                  <div class="tab-pane" id="tab3">
                    <div class="card">                                           
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-striped table-sm table-bordered" >
                            <thead class="thead-dark">
                              <tr class="text-center">
                                <th>SUC.</th>
                                <th>F. VEN</th>
                                <th>CANT.</th>	
                              </tr>
                            </thead>
                            <tbody>
                            <?php
                              include "../conexion.php";

                              $querystock = mysqli_query($conexion, "SELECT s.idsuc, s.fechavencimiento, s.saldo,
                                                                    sc.nombre
                                                                    FROM stock s
                                                                    INNER JOIN sucursal sc ON s.idsuc = sc.idsucursal
                                                                    WHERE idmedicamento = $idmedicamento");
                              $resultsrtock = mysqli_num_rows($querystock);
                              if ($resultsrtock> 0) {
                                while ($datastock = mysqli_fetch_assoc($querystock)) { 
                              ?>
                              <tr>
                                <td><?php echo $datastock['nombre']; ?></td>
                                <td class="text-center"><?php echo $datastock['fechavencimiento']; ?></td>																
                                <td class="text-center"><?php echo $datastock['saldo']; ?></td>											  
                              </tr>
                              <?php }
                                } ?>
                            </tbody>
                          </table>                    
                        </div> 
                          <?php
                            include "../conexion.php";

                            $querysumastock = mysqli_query($conexion, " SELECT SUM(saldo) AS stock FROM stock WHERE idmedicamento = $idmedicamento");
                            $datasumstock = mysqli_fetch_assoc($querysumastock)
                          ?>            
                          <label class="h5 text-verde font-weight-bold"><span class="h6 text-azul mr-2">TOTAL STOCK: </span><?php echo $datasumstock['stock']; ?><span class="h6 text-azul ml-2"> Unidades. </span></label> 
                      </div>
                    </div>
                  </div>
                   <!-- tabs - FINAL tercer elemento STOCK -->
                </div>
              </div>
            </div>
            <!-- FINAL TERCER CUADRANTE -->
            
            <!-- CUARTO CUADRANTE -->
            <div class="col-md-5">
              <div class="card">
                <div class="card-header">
                  <div class="d-sm-flex align-items-center justify-content-between mb-1">
                    <h1 class="h6 font-weight-bold  mb-0 text-uppercase text-azul">RESUMEN</h1>                    
                    <button type="button" class="btn btn-primary btn-sm view_kardex" idmed=""><i class="fas fa-print mr-2"></i>Kardex</button>				
                  </div>
                </div>                                           
                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped table-sm table-bordered" >
                        <thead class="thead-dark">
                          <tr class="text-center">
                            <th colspan="2">RESUMEN MOVIMIENTOS</th>                            	
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>UNIDADES INGRESADAS</td>                            															
                            <td class="text-center"><?php echo $datasum['cantidad']; ?></td>											  
                          </tr>
                          <tr>
                            <td>UNIDADES VENDIDAS</td>                            															
                            <td class="text-center"><?php echo $datacanventa['cantidad']; ?></td>											  
                          </tr>
                          <tr>
                            <td>UNIDADES EN STOCK</td>                            															
                            <td class="text-center"><?php echo $datasumstock['stock']; ?></td>											  
                          </tr>
                        </tbody>
                      </table>                    
                    </div> 
                </div>
                <div class="card-footer"> 
                  <label class="h5 text-verde font-weight-bold"><span class="h6 text-dark mr-2">IMPORTE POR VENTAS:</span>Bs. <?php echo $suma ?>,00<span class="h6 text-dark">  </span></label>                    
                </div>
              </div>
            </div>

          </div>
          <!-- FINAL SEGUNDA FILA -->   
        </div>
    </div> 
  <!-- FINAL ROW PRINCIPAL -->   
    </div>
    <!--  Final Cuerpo del detalle -->
  </div>
  <!-- carta general --> 
</div>
<!-- End Container fluid -->


</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>