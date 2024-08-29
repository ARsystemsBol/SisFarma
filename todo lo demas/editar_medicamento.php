<?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $alert = "no envia";
    if (empty($_POST['nommedicamento'])    
    || ($_POST['pactivo'] < 0)
    || ($_POST['formafarmaceutica'] < 0)    
    || ($_POST['acciont'] < 0)  
    || empty($_POST['concentracion'])    
    || ($_POST['unidadmed'] < 0)
    || ($_POST['preciounitario'] < 0)
    || ($_POST['cantidadminima'] < 0) ) {
      $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
    } else {
      $codmedicamento = $_GET['id'];
      $proveedor = $_POST['prov'];
      $nommedicamento = $_POST['nommedicamento'];
      $pactivo = $_POST['pactivo'];
      $formafarmaceutica = $_POST['formafarmaceutica'];
      $acciont = $_POST['acciont'];
      $concentracion = $_POST['concentracion'];
      $unidadmed = $_POST['unidadmed'];
      $presentacion = $_POST['presentacion'];
      $formula = $_POST['formula'];
      $indicaciones = $_POST['indicaciones'];
      $posologia = $_POST['posologia'];
      $contradicciones = $_POST['contradicciones'];
      $preciounitario = $_POST['preciounitario'];
      $cantidadminima = $_POST['cantidadminima'];  

      $query_update = mysqli_query($conexion, "UPDATE medicamento SET nombre = '$nommedicamento',
                                                    laboratorio = $proveedor, 
                                                    idpactivo = $pactivo, idforma = $formafarmaceutica,
                                                    idacciont = $acciont, concentracion = '$concentracion',
                                                    idunidad = $unidadmed, presentacion = '$presentacion',
                                                    formula = '$formula', indicaciones = '$indicaciones',
                                                    posologia = '$posologia', contradicciones = '$contradicciones', 
                                                    preciounitario = $preciounitario, cantidadminima = $cantidadminima
                                                    WHERE idmedicamento = $codmedicamento");
      if ($query_update) {
      $alert = '<div class="alert alert-success" role="alert">
              Datos modificados correctamente              
            </div>';
            
      } 
      else {
        $alert = '<div class="alert alert-primary" role="alert">
                Error al Modificar
              </div>';
      }
    }
  }
  // Validar producto

  if (empty($_REQUEST['id'])) {
    header("Location: lista_medicamentos.php");
  } else {
    $id_medicamento = $_REQUEST['id'];
    if (!is_numeric($id_medicamento)) {
      header("Location: lista_medicamentos.php");
    }
    $query_medicamento = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, 
                                                  m.concentracion, m.stock, m.presentacion, m.formula,
                                                  m.laboratorio,
                                                  m.indicaciones, m.posologia, m.contradicciones,
                                                  m.preciounitario, m.cantidadminima,
                                                  pa.medicamento AS despactivo, pa.id AS idpac,
                                                  f.nombre AS desforma, f.idforma AS idform,
                                                  ate.nombre AS desaterapeutica, ate.idaccion AS idacct,
                                                  un.abreviatura AS desunidad, un.idunidad AS iduni,
                                                  p.proveedor AS desproveedor
                                                  FROM medicamento m
                                                  INNER JOIN pactivo pa ON m.idpactivo = pa.id
                                                  INNER JOIN forma f ON m.idforma = f.idforma
                                                  INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
                                                  INNER JOIN unidades un ON m.idunidad = un.idunidad    
                                                  INNER JOIN proveedor p ON m.laboratorio = p.codproveedor                                                                                                
                                                  WHERE idmedicamento = $id_medicamento");
    $result_medicamento = mysqli_num_rows($query_medicamento);

    if ($result_medicamento > 0) {
      $data_medicamento = mysqli_fetch_assoc($query_medicamento);
    } else {
      header("Location: lista_medicamentos.php");
    }
  }      
      
?>

  <!-- Begin Page Content -->
  <div class="container-fluid pt-5 mt-5">
    <!-- Page breadcrumb -->	
    <nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	
        <li class="breadcrumb-item"><a href="lista_medicamentos.php"><i class="fas fa-tablets text-info"></i></a></li>
        <li class="breadcrumb-item"><i class="fas fa-pills text-info mr-2"></i><i class="fas fa-save text-info"></i></li>			
    		<li class="breadcrumb-item active" aria-current="page">Registro Medicamento</li>
  		</ol>
    </nav>
    <!-- End Page breadcrumb -->   
  
    <!-- Content Row -->   
    <div class="card">  
      <div class="card-header bg-gradient-dark text-white ">
        <div class="d-sm-flex align-items-center justify-content-between mb-1">
          <h1 class="h6 mb-0 text-uppercase">Registrar Medicamento</h1>
          <a href="lista_medicamentos.php" class="btn btn-danger btn-sm">Regresar</a>
        </div>
      </div>    
      <div class="card-body">
        <div class ="col-8 m-auto">
          <?php echo isset($alert) ? $alert : ''; ?>
        </div>
        <form class="row p-3" action="" method="post" autocomplete="off">
          <!-- PRIMERA COLUMNA --> 
          <div class="row col-6">
            <!--  LABORATORIOS--> 
            <div class="col-12 mb-4">
              <label>LABORATORIO<span class="text-danger">*</span></label>        
                <?php
                  $query_prov = mysqli_query($conexion, "SELECT codproveedor, proveedor FROM proveedor ORDER BY codproveedor ASC");
                  $resultado_prov = mysqli_num_rows($query_prov);           
                ?>
              <select id="prov" name="prov" class="form-control">
                <option value="<?php echo $data_medicamento['laboratorio']; ?>"> <?php echo $data_medicamento['desproveedor']; ?></option>
                  <?php
                    if ($resultado_prov > 0) {
                    while ($prov = mysqli_fetch_array($query_prov)) {
                      // code...
                  ?>
                <option value="<?php echo $prov['codproveedor']; ?>"><?php echo $prov['proveedor'];?></option>
                  <?php } } ?>
              </select>
            </div>

            <!-- NOMBRE COMERCIAL MEDICAMENTO--> 
            <div class="col-md-12 mb-4">
              <label for="nommedicamento" class="form-label">NOMBRE - COMERCIAL MEDICAMENTO<span class="text-danger">*</span></label>
              <input value="<?php echo $data_medicamento['nombre']; ?>" type="text" class="form-control" name = "nommedicamento" id="nommedicamento">
            </div>

            <!-- PRINCIPIO ACTIVO--> 
            <div class="col-12 mb-4">
              <label>PRINCIPIO ACTIVO<span class="text-danger">*</span></label>        
                <?php
                  $query_pa = mysqli_query($conexion, "SELECT id, medicamento FROM pactivo ORDER BY medicamento ASC");
                  $resultado_pa = mysqli_num_rows($query_pa);
                ?>
                <select id="pactivo" name="pactivo" class="form-control" autocomplete="on">
                  <option value="<?php echo $data_medicamento['idpac']; ?>"><?php echo $data_medicamento['despactivo']; ?></option>
                  <?php
                  if ($resultado_pa > 0) {
                    while ($pa = mysqli_fetch_array($query_pa)) {
                    // code...
                  ?>
                <option value="<?php echo $pa['id']; ?>"><?php echo $pa['medicamento']; ?></option>
                <?php
                  }
                  }
                ?>
              </select>
            </div>          

            <!-- FORMA FARMACEUTICA - CATEGORIA--> 
            <div class="col-6 mb-4">
              <label>FORMA FARMACEUTICA<span class="text-danger">*</span></label>        
                <?php
                  $query_forma = mysqli_query($conexion, "SELECT idforma, nombre FROM forma ORDER BY nombre ASC");
                  $resultado_forma = mysqli_num_rows($query_forma);           
                ?>
              <select id="formafarmaceutica" name="formafarmaceutica" class="form-control">
                <option value="<?php echo $data_medicamento['idform']; ?>"> <?php echo $data_medicamento['desforma']; ?></option>
                  <?php
                    if ($resultado_forma > 0) {
                    while ($forma = mysqli_fetch_array($query_forma)) {
                      // code...
                  ?>
                <option value="<?php echo $forma['idforma']; ?>"><?php echo $forma['nombre']; ?></option>
                  <?php } } ?>
              </select>
            </div>
          
            <!-- CONCETRACION--> 
            <div class="col-6">
              <label for="concentracion" class="form-label">CONCENTRACION<span class="text-danger">*</span></label>
              <input value="<?php echo $data_medicamento['concentracion']; ?>" type="text" class="form-control"  name = "concentracion" id="concentracion" placeholder="">
            </div>

            <!-- UNIDAD--> 
            <div class="col-4 md-3">
              <label>UNIDAD<span class="text-danger">*</span></label> 
                <?php
                  $query_unidad = mysqli_query($conexion, "SELECT idunidad, nombre, abreviatura FROM unidades ORDER BY nombre ASC");
                  $resultado_unidad = mysqli_num_rows($query_unidad);           
                ?>
              <select id="unidadmed" name="unidadmed" class="form-control">
                <option value="<?php echo $data_medicamento['iduni']; ?>"><?php echo $data_medicamento['desunidad']; ?></option>
                  <?php
                    if ($resultado_unidad > 0) {
                    while ($unidad = mysqli_fetch_array($query_unidad)) {
                    // code...
                  ?>
                <option value="<?php echo $unidad['idunidad']; ?>"><?php echo $unidad['nombre']; ?></option>
                  <?php } } ?>
              </select>     
            </div>

            <!-- ACCION TERAPEUTICA --> 
            <div class="col-8 mb-3">
              <label>ACCION TERAPÉUTICA<span class="text-danger">*</span></label>       
                <?php
                $query_acciont = mysqli_query($conexion, "SELECT idaccion, nombre FROM accionterapeutica ORDER BY nombre ASC");
                $resultado_acciont = mysqli_num_rows($query_acciont);
                ?>
              <select id="acciont" name="acciont" class="form-control">
                <option value="<?php echo $data_medicamento['idacct']; ?>"><?php echo $data_medicamento['desaterapeutica']; ?></option>
                  <?php
                    if ($resultado_acciont > 0) {
                    while ($acciont = mysqli_fetch_array($query_acciont)) {
                    // code...
                  ?>
                <option value="<?php echo $acciont['idaccion']; ?>"><?php echo $acciont['nombre']; ?></option>
                  <?php } } ?>
              </select>
            </div>
          
            <!-- PRESENTACION --> 
            <div class="col-md-12 mb-4">
              <label for="presentacion" class="form-label">PRESENTACION</label>
              <input value="<?php echo $data_medicamento['presentacion']; ?>" type="text" class="form-control"  name = "presentacion" id="presentacion">            
            </div>
          </div> 
          <!-- FIN DE LA PRIMERA COLUMNA-->

          <!-- SEGUNDA COLUMNA -->
          <div class="row col-6 mb-4"> 
            <!-- FORMULA --> 
            <div class="col-md-12 mb-4">
              <label for="formula" class="form-label">FORMULA</label>            
              <textarea class="form-control" id="formula" name="formula" rows="2" cols="40"><?php echo $data_medicamento['formula']; ?></textarea>           
            </div>

            <!-- INDICACIONES --> 
            <div class="col-md-12 mb-4">
              <label for="indicaciones" class="form-label">INDICACIONES</label>            
              <textarea class="form-control" id="indicaciones" name="indicaciones" rows="2" cols="40"><?php echo $data_medicamento['indicaciones']; ?></textarea>           
            </div>

            <!-- POSOLOGIA --> 
            <div class="col-md-12 mb-4">
              <label for="posologia" class="form-label">POSOLOGIA</label>            
              <textarea class="form-control" id="posologia" name="posologia" rows="2" cols="40"><?php echo $data_medicamento['posologia']; ?></textarea>           
            </div>

            <!-- CONTRADICCIONES --> 
            <div class="col-md-12 mb-4">
              <label for="contradicciones" class="form-label">CONTRADICCIONES</label>            
              <textarea class="form-control" id="contradicciones" name="contradicciones" rows="2" cols="40"><?php echo $data_medicamento['contradicciones']; ?></textarea>           
            </div>

            <!-- PRECIO UNITARIO--> 
            <div class="col-md-6 mb-4">
              <label for="preciounitario" class="form-label">PRECIO UNITARIO<span class="text-danger">*</span> </label>
              <input  value="<?php echo $data_medicamento['preciounitario']; ?>"type="text" class="form-control"  name = "preciounitario" id="preciounitario">
            </div> 
                
            <!-- STOCK MINIMO--> 
            <div class="col-md-6 mb-4">
              <label for="cantidadminima" class="form-label">STOCK MINIMO<span class="text-danger">*</span></label>
              <input value="<?php echo $data_medicamento['cantidadminima']; ?>" type="text" class="form-control"  name = "cantidadminima" id="cantidadminima">
            </div>
          </div>
          
          <!-- btn POST-->
          <div class="row col-6 "> 
            <div class="col-12">
              <input type="submit" value="Editar Medicamento" class="btn btn-success btn-sm mr-3">
              <a href="lista_medicamentos.php" class="btn btn-danger btn-sm">Cancelar</a>      
            </div>
          </div>
          <!-- FIN DE LA SEGUNDA COLUMNA--> 
        </form>
      </div>
    </div>
    <!-- End Content Row -->
  </div>
  <!-- /.container-fluid -->
  
  <!-- /anclaje -->
  </div>

<?php include_once "includes/footer.php"; ?>