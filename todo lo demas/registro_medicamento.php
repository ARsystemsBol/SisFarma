<?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $alert = "";
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
      $query = mysqli_query($conexion, "SELECT MAX(idmedicamento) FROM medicamento");
      $result = mysqli_num_rows($query); 
      if ($result > 0) {
          $data = mysqli_fetch_assoc($query);
      } 

      if ($data['MAX(idmedicamento)'] < 10 ) {
        $prefijocod = 'MED-0000';   
      }
      else if(($data['MAX(idmedicamento)'] > 9) && ($data['MAX(idmedicamento)'] < 99 ) ){
        $prefijocod = 'MED-000';
      }
      else if(($data['MAX(idmedicamento)'] > 99) && ($data['MAX(idmedicamento)'] < 999 ) ){
        $prefijocod = 'MED-00';
      }
      else if(($data['MAX(idmedicamento)'] > 999) && ($data['MAX(idmedicamento)'] < 9999 ) ){
        $prefijocod = 'MED-0';

      }else if($data['MAX(idmedicamento)'] > 99999 ){
        $prefijocod = 'MED-';
      }
      
     
      $ultimovalor = $data['MAX(idmedicamento)']; 
      $codmedicamento = $prefijocod . $ultimovalor + 1 ;
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

      $query_insert = mysqli_query($conexion, "INSERT INTO medicamento(codmedicamento, laboratorio, nombre, idpactivo, idforma, idacciont, 
                                                           concentracion, idunidad, presentacion, formula, indicaciones, posologia, contradicciones, preciounitario, cantidadminima) 
                                               VALUES ('$codmedicamento', $proveedor, '$nommedicamento', $pactivo, '$formafarmaceutica', 
                                                      $acciont,'$concentracion', $unidadmed, '$presentacion', '$formula', '$indicaciones',
                                                      '$posologia', '$contradicciones', $preciounitario, $cantidadminima)");
      if ($query_insert) {
        $alert = '<div class="alert alert-success" role="alert">
                Medicamento Registrado
              </div>';
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                Error al Registrar el Medicamento
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
            <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>
            <li class="breadcrumb-item"><a href="lista_medicamentos.php"><i class="fas fa-tablets text-info"></i></a>
            </li>
            <li class="breadcrumb-item"><i class="fas fa-pills text-info mr-2"></i><i class="fas fa-save text-info"></i>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Registro Medicamento</li>
        </ol>
    </nav> -->
    <!-- End Page breadcrumb -->

    <!-- Content Row -->
    <div class="card">
        <div class="card-header bg-azul text-white ">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h6 mb-0 text-uppercase">Registrar Medicamento</h1>
                <a href="lista_medicamentos.php" class="btn btn-danger btn-sm">Regresar</a>
            </div>
        </div>
        <div class="card-body">
            <div class="col-8">
                <?php echo isset($alert) ? $alert : ''; ?>
            </div>
            <form class="row" action="" method="post" autocomplete="off">
                <!-- PRIMERA COLUMNA -->
                <div class="row col-6">

                    <!-- LABORATORIO-->
                    <div class="col-md-12 mb-2">
                        <label>LABORATORIO<span class="text-danger"> *</span></label>
                        <?php
                          $query_prov = mysqli_query($conexion, "SELECT codproveedor, proveedor 
                                                              FROM proveedor 
                                                              ORDER BY codproveedor ASC");
                          $resultado_prov = mysqli_num_rows($query_prov);           
                        ?>
                        <select id="prov" name="prov" class="form-control form-control-sm">
                            <option></option>
                            <?php
                              if ($resultado_prov > 0) {
                              while ($prov = mysqli_fetch_array($query_prov)) {
                                // code...
                            ?>
                            <option value="<?php echo $prov['codproveedor']; ?>">
                                <?php echo $prov['proveedor']; ?>
                            </option>
                            <?php } } ?>
                        </select>
                    </div>

                    <!-- NOMBRE COMERCIAL MEDICAMENTO-->
                    <div class="col-md-12 mb-2">
                        <label for="nommedicamento" class="form-label">NOMBRE - COMERCIAL MEDICAMENTO<span
                                class="text-danger"> *</span></label>
                        <input autofocus type="text" class="form-control form-control-sm" name="nommedicamento"
                            id="nommedicamento">
                    </div>

                    <!-- PRINCIPIO ACTIVO-->
                    <div class="col-12 mb-2">
                        <label>PRINCIPIO ACTIVO<span class="text-danger"> *</span></label>
                        <?php
                          $query_pa = mysqli_query($conexion, "SELECT id, medicamento FROM pactivo ORDER BY medicamento ASC");
                          $resultado_pa = mysqli_num_rows($query_pa);
                    
                        ?>
                        <select id="pactivo" name="pactivo" class="form-control form-control-sm" autocomplete="on">
                            <option></option>
                            <?php
                        if ($resultado_pa > 0) {
                          while ($pa = mysqli_fetch_array($query_pa)) {
                          // code...
                        ?>
                            <option value="<?php echo $pa['id']; ?>">
                                <?php echo $pa['medicamento']; ?>
                            </option>
                            <?php
                          }
                          }
                        ?>
                        </select>
                    </div>

                    <!-- FORMA FARMACEUTICA - CATEGORIA-->
                    <div class="col-6 mb-2">
                        <label>FORMA FARMACEUTICA<span class="text-danger"> *</span></label>
                        <?php
                          $query_forma = mysqli_query($conexion, "SELECT idforma, nombre FROM forma ORDER BY nombre ASC");
                          $resultado_forma = mysqli_num_rows($query_forma);           
                        ?>
                        <select id="formafarmaceutica" name="formafarmaceutica" class="form-control form-control-sm">
                            <option></option>
                            <?php
                              if ($resultado_forma > 0) {
                              while ($forma = mysqli_fetch_array($query_forma)) {
                                // code...
                            ?>
                            <option value="<?php echo $forma['idforma']; ?>">
                                <?php echo $forma['nombre']; ?>
                            </option>
                            <?php } } ?>
                        </select>
                    </div>

                    <!-- CONCETRACION-->
                    <div class="col-6 mb-2">
                        <label for="concentracion" class="form-label">CONCENTRACION<span class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control form-control-sm" name="concentracion" id="concentracion"
                            placeholder="">
                    </div>

                    <!-- UNIDAD-->
                    <div class="col-4 md-2">
                        <label>UNIDAD<span class="text-danger">*</span></label>
                        <?php
                          $query_unidad = mysqli_query($conexion, "SELECT idunidad, nombre, abreviatura FROM unidades ORDER BY nombre ASC");
                          $resultado_unidad = mysqli_num_rows($query_unidad);           
                        ?>
                        <select id="unidadmed" name="unidadmed" class="form-control form-control-sm">
                            <option></option>
                            <?php
                              if ($resultado_unidad > 0) {
                              while ($unidad = mysqli_fetch_array($query_unidad)) {
                              // code...
                            ?>
                            <option value="<?php echo $unidad['idunidad']; ?>">
                                <?php echo $unidad['nombre']; ?>
                            </option>
                            <?php } } ?>
                        </select>
                    </div>

                    <!-- ACCION TERAPEUTICA -->
                    <div class="col-8 mb-2">
                        <label>ACCION TERAPÉUTICA<span class="text-danger"> *</span></label>
                        <?php
                          $query_acciont = mysqli_query($conexion, "SELECT idaccion, nombre FROM accionterapeutica ORDER BY nombre ASC");
                          $resultado_acciont = mysqli_num_rows($query_acciont);
                          ?>
                        <select id="acciont" name="acciont" class="form-control form-control-sm">
                            <option></option>
                            <?php
                              if ($resultado_acciont > 0) {
                              while ($acciont = mysqli_fetch_array($query_acciont)) {
                              // code...
                            ?>
                            <option value="<?php echo $acciont['idaccion']; ?>">
                                <?php echo $acciont['nombre']; ?>
                            </option>
                            <?php } } ?>
                        </select>
                    </div>

                    <!-- PRESENTACION -->
                    <div class="col-md-12">
                        <label for="presentacion" class="form-label">PRESENTACION</label>
                        <input type="text" class="form-control form-control-sm" name="presentacion" id="presentacion">
                    </div>
                </div>
                <!-- FIN DE LA PRIMERA COLUMNA-->

                <!-- SEGUNDA COLUMNA -->
                <div class="row col-6">
                    <!-- FORMULA -->
                    <div class="col-md-12 mb-3">
                        <label for="formula" class="form-label">FORMULA</label>
                        <textarea class="form-control form-control-sm" id="formula" name="formula" rows="4"
                            cols="40"></textarea>
                    </div>

                    <!-- INDICACIONES -->
                    <div class="col-md-12 mb-3">
                        <label for="indicaciones" class="form-label">INDICACIONES</label>
                        <textarea class="form-control form-control-sm" id="indicaciones" name="indicaciones" rows="4"
                            cols="40"></textarea>
                    </div>

                    <!-- POSOLOGIA -->
                    <div class="col-md-12 mb-3">
                        <label for="posologia" class="form-label">POSOLOGIA</label>
                        <textarea class="form-control form-control-sm" id="posologia" name="posologia" rows="4"
                            cols="40"></textarea>
                    </div>

                    <!-- CONTRAINDICACIONES -->
                    <div class="col-md-12 mb-4">
                        <label for="contradicciones" class="form-label">CONTRAINDICACIONES</label>
                        <textarea class="form-control form-control-sm" id="contradicciones" name="contradicciones"
                            rows="3" cols="40"></textarea>
                    </div>

                    <!-- PRECIO UNITARIO-->
                    <div class="col-md-6 mb-4">
                        <label for="preciounitario" class="form-label">PRECIO UNITARIO<span class="text-danger">
                                *</span> </label>
                        <input type="text" class="form-control form-control-sm" name="preciounitario"
                            id="preciounitario">
                    </div>

                    <!-- STOCK MINIMO-->
                    <div class="col-md-6 mb-4">
                        <label for="cantidadminima" class="form-label">STOCK MINIMO<span class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control form-control-sm" name="cantidadminima"
                            id="cantidadminima">
                    </div>
                </div>

                <!-- btn POST-->
                <div class="row col-12">
                    <div class="col-12">
                        <input type="submit" value="Guardar Medicamento" class="btn btn-success btn-sm mr-3">
                        <a href="registro_medicamento.php" class="btn btn-danger btn-sm">Cancelar</a>
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