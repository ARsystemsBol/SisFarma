 <?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nomForma'])) {
      $alert = '<div class="alert alert-danger" role="alert">
                El campo es obligatorio
              </div>';
    } else {
      $Nombforma = $_POST['nomForma'];      

      $query_insert = mysqli_query($conexion, "INSERT INTO forma(nombre) values ('$Nombforma')");
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
<!-- <nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	
        <li class="breadcrumb-item"><a href="lista_medicamentos.php"><i class="fas fa-tablets text-info"></a></i></li>
        <li class="breadcrumb-item"><a href="lista_formafarmaceutica.php"><i class="fas fa-capsules text-info"></a></i></li>
        <li class="breadcrumb-item"><i class="fas fa-capsules text-info mr-2"></i><i class="fas fa-save text-info"></i></li>				
    		<li class="breadcrumb-item active" aria-current="page">Registro Forma farmaceutica</li>
  		</ol>
</nav> -->

    <!-- Formulario registro de forma farmaceutica -->
    <div class="row">     
      <div class="col-lg-7">
        <div class="card"> 
          <div class="card-header bg-azul text-white ">
           LISTA F. FARMACEÚTICA     
          </div>    
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-sm table-bordered" id="table">
                <thead class="thead-dark">
                  <tr class="text-center aligin-middle" style="font-size:12px;">
                    <th>ID</th>
                    <th>FORMA FARMACÉUTICA</th>														
                    <?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
                    <th>ACCIONES</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  include "../conexion.php";

                  $query = mysqli_query($conexion, "SELECT * FROM forma");
                  $result = mysqli_num_rows($query);
                  if ($result > 0) {
                    while ($data = mysqli_fetch_assoc($query)) { ?>
                      <tr class="aligin-middle"  style="font-size:14px;">
                        <td class="text-center"><?php echo $data['idforma']; ?></td>
                        <td><?php echo $data['nombre']; ?></td>																	
                        <?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
                        <td class="text-center">                                                    
                          <a href="editar_forma.php?id=<?php echo $data['idforma']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>
                          <?php if ($_SESSION['rol'] == 1) { ?>  
                          <form action="eliminar_forma.php?id=<?php echo $data['idforma']; ?>" method="post" class="confirmar d-inline">
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
      <div class="col-lg-5">
        <div class="card"> 
          <div class="card-header bg-azul text-white ">
            <div class="d-sm-flex align-items-center justify-content-between">
              <h1 class="h6 mb-0 text-uppercase">Registrar Forma Farmacéutica</h1>
              <a href="lista_medicamentos.php" class="btn btn-danger btn-sm">Regresar</a>
            </div>
          </div>    
          <div class="card-body">
            <div class ="col-8 m-auto">
              <?php echo isset($alert) ? $alert : ''; ?>
            </div>
            <form class="row col-12 m-auto p-3" action="" method="post" autocomplete="off">
              <!-- TIPO DE USO DEL MEDICAMENTO--> 
              <div class="col-12 mb-4 ">
                    <label>Nombre - Forma Farmacéutica</label>           
                    <input autofocus type="text" placeholder="" name="nomForma" id="nomForma" class="form-control form-control-sm">  
              </div>
              <div class="col-8">
                    <input type="submit" value="Registrar Forma " class="btn btn-primary btn-sm">
              </div>
            </form> 
          </div>
        </div>        
      </div>
    </div>

    
    


</div>   
 <!-- /.container-fluid -->

 </div>
 <!-- End of Main Content -->
 <?php include_once "includes/footer.php"; ?>