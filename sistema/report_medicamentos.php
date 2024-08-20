<?php include_once "includes/header.php";
  include "../conexion.php";
    
?>

  <!-- Begin Page Content -->
  <div class="container-fluid pt-5 mt-5">
    <!-- Page breadcrumb -->	
<!--     <nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-azul"></a></i></li>	
        <li class="breadcrumb-item"><a href="lista_medicamentos.php"><i class="fas fa-tablets text-azul"></i></a></li>
        <li class="breadcrumb-item"><i class="fas fa-pills text-azul mr-2"></i><i class="fas fa-print text-azul"></i></li>			
    		<li class="breadcrumb-item active" aria-current="page">Reportes Medicamentos</li>
  		</ol>
    </nav> -->
    <!-- End Page breadcrumb -->   
  
    <!-- Content Row -->   
    <div class="card">  
      <div class="card-header bg-azul text-white ">
        <div class="d-sm-flex align-items-center justify-content-between">
          <h1 class="h6 mb-0 text-uppercase">Generar Reportes -  Medicamentos</h1>
          <a href="index.php" class="btn btn-danger btn-sm">Regresar</a>
        </div>
      </div>    
      <div class="card-body">
        <div class="row">
          <!-- Reporte x General -->   
          <div class="col-md-3">          
            <div class="card">  
              <div class="card-header bg-azul text-white ">
                <div class="d-sm-flex align-items-center justify-content-between mb-1">
                  <h1 class="h6 mb-0 text-uppercase">General</h1>
                </div>
              </div>    
              <div class="card-body">
                
              </div>          
            </div>         
          </div>
          <!-- .Reporte x general -->  

          <!-- Reporte x General -->   
          <div class="col-md-3">          
            <div class="card">  
              <div class="card-header bg-verde text-white ">
                <div class="d-sm-flex align-items-center justify-content-between mb-1">
                  <h1 class="h6 mb-0 text-uppercase">x Laboratorio</h1>
                </div>
              </div>    
              <div class="card-body">
                <!-- LABORATORIO-->
                <div class="col-12 mb-3">
                    <label>LABORATORIO<span class="text-danger"> *</span></label>
                      <?php
                        $query_prov = mysqli_query($conexion, "SELECT codproveedor, lab FROM proveedor ORDER BY codproveedor ASC");
                        $resultado_prov = mysqli_num_rows($query_prov);           
                      ?>
                    <select id="prov" name="prov" class="form-control form-control-sm">
                        <option></option>
                        <?php
                          if ($resultado_prov > 0) {
                          while ($prov = mysqli_fetch_array($query_prov)) {
                            // code...
                        ?>
                        <option value="<?php echo $prov['codproveedor']; ?>"><?php echo $prov['lab']; ?> </option>
                         
                        <?php  } } ?>

                    </select>
                </div>
                <div class="col-12">  
                  <input hidden type="number" class="form-control form-control-sm mb-4" id="prove" name="prove">                
                  <button type="button" class="btn btn-danger btn-sm pr-3 pl-3 view_rxl" l=""><i class="fas fa-print mr-2"> </i>Imprimir</button>
                </div>
                
              </div>          
            </div>         
          </div>
          <!-- .Reporte x general -->  

           <!-- Reporte x General -->   
           <div class="col-md-3">          
            <div class="card">  
              <div class="card-header bg-dark text-white ">
                <div class="d-sm-flex align-items-center justify-content-between mb-1">
                  <h1 class="h6 mb-0 text-uppercase">x Stock</h1>
                </div>
              </div>    
              <div class="card-body">
                
              </div>          
            </div>         
          </div>
          <!-- .Reporte x general -->  

           <!-- Reporte x General -->   
           <div class="col-md-3">          
            <div class="card">  
              <div class="card-header bg-verde text-white ">
                <div class="d-sm-flex align-items-center justify-content-between mb-1">
                  <h1 class="h6 mb-0 text-uppercase">x Laboratorio</h1>
                </div>
              </div>    
              <div class="card-body">
                
              </div>          
            </div>         
          </div>
          <!-- .Reporte x general -->  
        </div>
      </div>
    </div>
    <!-- End Content Row -->
  </div>
  <!-- /.container-fluid -->
  
  <!-- /anclaje -->
  </div>

<?php include_once "includes/footer.php"; ?>