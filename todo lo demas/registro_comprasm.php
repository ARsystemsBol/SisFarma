<?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $alert = "";
    if ((($_POST['montogasto']) < 0) || empty($_POST['motivogasto']) || empty($_POST['notagasto'])) {
      $alert = '<div class="alert alert-danger" role="alert">
                Todos los campos son obligatorios
              </div>';
    } else {
      $user = $_SESSION['idUser'];
      $monto = $_POST['montogasto'];
      $motivo = $_POST['motivogasto']; 
      $nota = $_POST['notagasto'];  
      $fecha_actual = date("Y-m-d"); 
      
      $query_ccajas = mysqli_query($conexion, "SELECT egresos FROM cajas WHERE fecha = '$fecha_actual' AND idusuario = $user");
      $resultado_ccajas = mysqli_num_rows($query_ccajas);
      if ($resultado_ccajas > 0) {
        $dataventas = mysqli_fetch_assoc($query_ccajas);
        $reg_monto = $dataventas['egresos'];
        $nueva_compra = $monto;
        $nuevo_monto = $reg_monto + $nueva_compra;
        $nuevo_monto = number_format($nuevo_monto, 2, '.', '');

        $query_update = mysqli_query($conexion, "UPDATE cajas SET egresos = $nuevo_monto WHERE fecha = '$fecha_actual' AND idusuario = $user");
      } 
      
     

      $query_insert = mysqli_query($conexion, "INSERT INTO egresos(monto,motivo,nota, idusuario) 
                                                VALUES ($monto, '$motivo', '$nota', $user)");
      if ($query_insert) {
        
        $alert = '<div class="alert alert-success" role="alert">
               Egreso Registrado
              </div>';        
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el egreso
              </div>';
      }
    }
  }
  ?>
  <!-- Begin Page Content -->
  <div class="container-fluid pt-5 mt-5">
    <!-- Page breadcrumb -->	
 <!--    <nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-azul"></a></i></li>	       
        <li class="breadcrumb-item"><i class="fas fa-coins text-warning mr-2"></i><i class="fas fa-save text-verde"></i></li>			
    		<li class="breadcrumb-item active" aria-current="page">Compras menores</li>
  		</ol>
    </nav> -->
    <!-- End Page breadcrumb -->   
  
    <div class="row"> 
      <div class="col-lg-7">
        <div class="card">  
          <div class="card-header bg-azul text-white ">
            <div class="d-sm-flex align-items-center justify-content-between">
              <h1 class="h6 mb-0 text-uppercase">Lista Compras</h1>         
            </div>
          </div>    
          <div class="card-body">       
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered" id="table">
                  <thead class="thead-dark">
                    <tr class="text-center aligin-middle" style="font-size:12px;">
                      <th>ID</th>						
                      <th>MONTO</th>						
                      <th>MOTIVO</th>      
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    include "../conexion.php";
                    $user = $_SESSION['idUser'];
                    $query = mysqli_query($conexion, "SELECT * FROM egresos WHERE idusuario = $user");
                    $result = mysqli_num_rows($query);                   
                    if ($result > 0) {
                      while ($data = mysqli_fetch_assoc($query)) { ?>
                        <tr style="font-size:14px;">
                          <td class="text-center"><?php echo $data['id']; ?></td>								
                          <td ><?php echo $data['monto']; ?></td>							
                          <td class=""><?php echo $data['motivo']; ?></td>
                        </tr>
                    <?php }
                    } ?>
                  </tbody>
                </table>
            </div>
        
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card mb-4">  
          <div class="card-header bg-azul text-white ">
            <div class="d-sm-flex align-items-center justify-content-between">
              <h1 class="h6 mb-0 text-uppercase">Registrar Gasto</h1>
              <a href="index.php" class="btn btn-danger btn-sm">Regresar</a>
            </div>
          </div>    
          <div class="card-body">
            <div class ="col-12 m-auto">
              <?php echo isset($alert) ? $alert : ''; ?>
            </div>        
            <form class="row col-12 m-auto p-3" action="" method="post" autocomplete="off">
              <!-- MONTO--> 
              <div class="col-6 mb-4">
                  <label for="montogasto">Monto</label>           
                  <input autofocus type="number" name="montogasto" id="montogasto" class="form-control form-control-sm">   
              </div>
              <!-- MOTIVO --> 
              <div class="col-12 mb-4">
                    <label for="motivogasto">Motivo</label>
                    <input type="text" name="motivogasto" id="motivogasto" class="form-control form-control-sm">    
              </div>
              <!-- NOTA --> 
              <div class="col-12 mb-4">
                    <label for="notagasto">Nota</label>
                    <input type="text" name="notagasto" id="notagasto" class="form-control form-control-sm">    
              </div>
              <div class="col-12 mb-4">        
                    <input type="submit" value="Registrar Gasto " class="btn btn-success btn-sm">
              </div>
            </form> 
          </div>
        </div>
      </div>
    </div>

    
    <!-- End Content Row -->
  </div>
  <!-- /.container-fluid -->
  
  <!-- /anclaje -->
  </div>

<?php include_once "includes/footer.php"; ?>