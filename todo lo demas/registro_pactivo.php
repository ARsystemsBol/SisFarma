 <?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['medicamentopa']) || ($_POST['usopa'] < 0))  {
      $alert = '<div class="alert alert-danger" role="alert">
                Todos los campos son obligatorios
              </div>';    
      
    } else { 
      $medicamentopa = $_POST['medicamentopa'];      
      $usopa = $_POST['usopa'];
      $query_insert = mysqli_query($conexion, "INSERT INTO pactivo(medicamento, uso ) 
                                                values ('$medicamentopa', $usopa)");
      if ($query_insert) {
        $alert = '<div class="alert alert-success" role="alert">
                Registro Completo
              </div>';
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar 
              </div>';
      }
    }
  }
  ?>

 <!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

  <!-- Page breadcrumb -->	
<!--   <nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	
        <li class="breadcrumb-item"><a href="lista_medicamentos.php"><i class="fas fa-tablets text-info"></i></a></li>
        <li class="breadcrumb-item"><a href="lista_pactivo.php"><i class="fas fa-vial text-info"></i></a></li>
        <li class="breadcrumb-item"><i class="fas fa-vial text-info mr-2"></i><i class="fas fa-save text-info"></i></li>			
    		<li class="breadcrumb-item active" aria-current="page">Registro Principio Activo</li>
  		</ol>
  </nav> -->
  <!-- End Page breadcrumb -->
  
  <div class="row"> 
    <div class="col-lg-8">
      <div class="card"> 
        <div class="card-header bg-azul text-white ">
          LISTA P. ACTIVO          
        </div>    
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="table">
              <thead class="thead-dark">
                <tr class="text-center aligin-middle" style="font-size:12px;">
                  <th>ID</th>						
                  <th>PRINCIPIO ACTIVO</th>						
                  <th>USO</th>
                  <?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
                  <th>ACCIONES</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php
                include "../conexion.php";
                $query = mysqli_query($conexion, "SELECT pa.id as idpa, pa.medicamento, pa.uso,
                                    u.descripcion															
                                    FROM pactivo pa
                                    INNER JOIN uso u ON pa.uso = u.id");
                $result = mysqli_num_rows($query);
                if ($result > 0) {
                  while ($data = mysqli_fetch_assoc($query)) { ?>
                    <tr style="font-size:14px;">
                      <td class="text-center"><?php echo $data['idpa']; ?></td>								
                      <td ><?php echo $data['medicamento']; ?></td>							
                      <td class=""><?php echo $data['descripcion']; ?></td>	
                      <?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
                      <td class="text-center">
                        <a href="editar_pactivo.php?id=<?php echo $data['idpa']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>
                        <?php if ($_SESSION['rol'] == 1)  { ?>
                        <form action="eliminar_pactivo.php?id=<?php echo $data['idpa']; ?>" method="post" class="confirmar d-inline">
                          <button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
                        </form>
                        <?php } ?>
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

    <div class="col-lg-4">
      <div class="card"> 
        <div class="card-header bg-azul text-white ">
          <div class="d-sm-flex align-items-center justify-content-between">
            <h1 class="h6 mb-0 text-uppercase">Registrar P. Activo - LINAME</h1>
            <a href="lista_medicamentos.php" class="btn btn-danger btn-sm">Regresar</a>
          </div>
        </div>    
        <div class="card-body">
          <div class ="col-8 m-auto">
            <?php echo isset($alert) ? $alert : ''; ?>
          </div>      
          <form class="row col-12 m-auto p-3" action="" method="post" autocomplete="off">
            <!-- NOMBRE MEDICAMENTO LISTADO LINAME--> 
            <div class="col-md-12 mb-2">
              <label for="medicamentopa" class="form-label ">NOMBRE <span class="text-danger"> *</span> </label>
              <input type="text" class="form-control form-control-sm" name = "medicamentopa" id="medicamentopa">
            </div>             
            <!-- USO --> 
            <div class="col-md-6">
              <label>UNIDAD</label> 
                <?php
                  $query_usupa = mysqli_query($conexion, "SELECT id, descripcion FROM uso ORDER BY descripcion ASC");
                  $resultado_usupa = mysqli_num_rows($query_usupa);
                
                  ?>
                <select id="usopa" name="usopa" class="form-control form-control-sm mb-4">
                  <?php
                    if ($resultado_usupa > 0) {
                      while ($usupa = mysqli_fetch_array($query_usupa)) {
                        // code...
                    ?>
                      <option value="<?php echo $usupa['id']; ?>"><?php echo $usupa['descripcion']; ?></option>
                  <?php
                      }
                    }
                    ?>
                </select>
          
            </div> 
            <div class="col-12">
              <input type="submit" value="Guardar" class="btn btn-success btn-sm">
              <a href="registro_pactivo.php" class="btn btn-danger btn-sm">Cancelar</a>
            </div>
          </form> 
        </div> 
      </div>    
    </div>


 </div>
 <!-- /.container-fluid -->

 </div>
 </div>
 <!-- End of Main Content -->
 <?php include_once "includes/footer.php"; ?>