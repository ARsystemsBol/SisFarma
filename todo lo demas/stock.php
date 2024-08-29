<?php include_once "includes/header.php";
  include "../conexion.php";
    
?>

  <!-- Begin Page Content -->
  <div class="container-fluid pt-5 mt-5">
    <!-- Page breadcrumb -->	
  <!--   <nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-azul"></a></i></li>	
        <li class="breadcrumb-item"><a href="lista_medicamentos.php"><i class="fas fa-tablets text-rojo"></i></a></li>
        <li class="breadcrumb-item"><i class="fas fa-pills text-verde mr-2"></i><i class="fas fa-boxes text-cafe"></i></li>			
    		<li class="breadcrumb-item active" aria-current="page">STOCK</li>
  		</ol>
    </nav> -->
    <!-- End Page breadcrumb -->   
  
    <!-- Content Row -->   
    <div class="card mb-4">  
      <div class="card-header bg-azul text-white ">
        <div class="d-sm-flex align-items-center justify-content-between">
          <h1 class="h6 mb-0 text-uppercase">Stock General</h1>
          <a href="index.php" class="btn btn-danger btn-sm">Regresar</a>
        </div>
      </div>    
      <div class="card-body"> 
      <!-- TABLA GENERAL -->       
        <div class="row">
          <div class="col-lg-12">
            <div class="table-responsive">
              <table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
                  <tr class="text-center align-middle" style="font-size:12px">
                    <th>ID</th>						
                    <th>NOMBRE</th>
                    <th>FECHA VEN.</th>
                    <th>STOCK</th>
                    <th>LAB.</th>					
                    <th>SUC.</th>							
                    <?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
                    <th>DETALLE</th>
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
                                                    WHERE saldo > 0");
                  $result = mysqli_num_rows($query);
                  if ($result > 0) {
                    while ($data = mysqli_fetch_assoc($query)) { 
                      $data['nombrecompleto'] = $data['nombre'].' ' . $data['desforma'].' ' . $data['concentracion'].' ' . $data['desunidad'];
                      
                      ?>							
                      <tr>
                        <td class="text-center align-middle" style="font-size:14px"><?php echo $data['correlativo']; ?></td>								
                        <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'];?></td>								
                        <td class="align-middle text-center" style="font-size:14px"><?php echo $data['fechavencimiento']?></td>
                        <td class="align-middle text-center" style="font-size:14px"><?php echo $data['saldo']?></td>			
                        <td class="align-middle text-center" style="font-size:14px"><?php echo $data['deslaboratorio']?></td>			
                        <td class="align-middle" style="font-size:14px"><?php echo $data['dessucursal']?></td>									
                        
                        <td class="text-center align-middle" style="font-size:14px">
                          <a href="detalle_medicamento.php?id=<?php echo $data['vermedicamento']; ?>" class="btn btn-info btn-sm  "><i class='fas fa-eye fa-sm'></i></a>
                                                 
                        </td>
                         
                      </tr>
                      
                  <?php  }
                  } ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
    <!-- End Content Row -->

    <!-- Content Row -->   
   <!--  <div class="card">  
      <div class="card-header bg-verde text-white ">
        <div class="d-sm-flex align-items-center justify-content-between">
          <h1 class="h6 mb-0 text-uppercase">Stock en la Sucursal</h1>
          <a href="index.php" class="btn btn-danger btn-sm">Regresar</a>
        </div>
      </div>    
      <div class="card-body">      -->             
        <!-- TABLA SUCURSAL -->       
        <!-- <div class="row">
          <div class="col-lg-12">
            <div class="table-responsive">
              <table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
                  <tr class="align-middle text-center" style="font-size:12px">
                    <th>ID</th>						
                    <th>NOMBRE</th>
                    <th>FECHA VEN.</th>
                    <th>STOCK</th>
                    <th>LAB.</th>					
                    <th>SUC.</th>	
                    <th>DETALLE</th>                   
                  </tr>
                </thead>
                <tbody>
                  <?php
                  include "../conexion.php";
                  $ids = $_SESSION['idsuc'];
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
                                                    WHERE idsuc = $ids AND saldo > 0");
                  $result = mysqli_num_rows($query);
                  if ($result > 0) {
                    while ($data = mysqli_fetch_assoc($query)) { 
                      $data['nombrecompleto'] = $data['nombre'].' ' . $data['desforma'].' ' . $data['concentracion'].' ' . $data['desunidad'];
                                         
                      ?>							
                      <tr>
                        <td class="text-center align-middle" style="font-size:14px"><?php echo $data['correlativo']; ?></td>								
                        <td class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'];?></td>								
                        <td class="align-middle text-center" style="font-size:14px"><?php echo $data['fechavencimiento']?></td>
                        <td class="align-middle text-center" style="font-size:14px"><?php echo $data['saldo']?></td>			
                        <td class="align-middle text-center" style="font-size:14px"><?php echo $data['deslaboratorio']?></td>			
                        <td class="align-middle" style="font-size:14px"><?php echo $data['dessucursal']?></td>									
                         
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

      
    </div> -->
    <!-- End Content Row -->
    

  </div>
  <!-- /.container-fluid -->
  
  <!-- /anclaje -->
  </div>

<?php include_once "includes/footer.php"; ?>