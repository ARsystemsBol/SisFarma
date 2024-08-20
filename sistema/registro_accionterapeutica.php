 <?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nomaccionterapeutica'])) {
      $alert = '<div class="alert alert-danger" role="alert">
                El Campo es Obligatorio
              </div>';    
      
    } else {
      $nomaccion = $_POST['nomaccionterapeutica'];       

      $query_insert = mysqli_query($conexion, "INSERT INTO accionterapeutica(nombre) values ('$nomaccion')");
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
        <li class="breadcrumb-item"><a href="lista_medicamentos.php"><i class="fas fa-pills text-info"></a></i></li>
        <li class="breadcrumb-item"><a href="lista_accionterapeutica.php"><i class="fas fa-stethoscope text-info"></a></i></li>			
        <li class="breadcrumb-item"><i class="fas fa-stethoscope text-info mr-2"></i><i class="fas fa-save text-info"></i></li>			
    		<li class="breadcrumb-item active" aria-current="page">Registro Accion Terapeutica</li>
  		</ol>
  </nav> -->
  <!-- End Page breadcrumb -->
  
  <div class="row"> 
    <div class="col-lg-7">
      <div class="card-header bg-azul text-white ">
       LISTA ACCION TERAPÉUTICA     
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-sm table-bordered" id="table">
            <thead class="thead-dark">
              <tr class="text-center aligin-middle" style="font-size:12px;">
                <th>ID</th>							
                <th>ACCION TEREPEUTICA</th>														
                <?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
                <th>ACCIONES</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php
              include "../conexion.php";

              $query = mysqli_query($conexion, "SELECT * FROM accionterapeutica");
              $result = mysqli_num_rows($query);
              if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) { ?>
                  <tr class="aligin-middle" style="font-size:14px;">
                    <td  class="text-center"><?php echo $data['idaccion']; ?></td>									
                    <td><?php echo $data['nombre']; ?></td>																			
                      <?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
                    <td class="text-center">                      
                      <a href="editar_accion.php?id=<?php echo $data['idaccion']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>
                      <?php if ($_SESSION['rol'] == 1)  { ?>
                      <form action="eliminar_accion.php?id=<?php echo $data['idaccion']; ?>" method="post" class="confirmar d-inline">
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
    <div class="col-lg-5">
      <div class="card"> 
        <div class="card-header bg-azul text-white ">
          <div class="d-sm-flex align-items-center justify-content-between">
            <h1 class="h6 mb-0 text-uppercase">Registrar Accion Terapeutica</h1>
            <a href="lista_medicamentos.php" class="btn btn-danger btn-sm">Regresar</a>
          </div>
        </div>    
        <div class="card-body">
          <div class ="col-8 m-auto">
            <?php echo isset($alert) ? $alert : ''; ?>
          </div>  
          <form class="row col-12 m-auto p-3" action="" method="post" autocomplete="off">
            <!-- NOMBRE ACCION TERAPEUTICA--> 
            <div class="col-md-12 mb-4">
              <label for="nommedicamento" class="form-label mb-4">NOMBRE - ACCION TERAPEUTICA</label>
              <input autofocus type="text" class="form-control "  name = "nomaccionterapeutica" id="nomaccionterapeutica">
            </div>      
            <div class="col-12">
              <input type="submit" value="Guardar" class="btn btn-success btn-sm">
              <a href="registro_accionterapeutica.php" class="btn btn-danger btn-sm">Cancelar</a>    
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