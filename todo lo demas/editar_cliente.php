<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['nombre'])  
  || empty($_POST['direccion'])) {
    $alert = '<div class="alert alert-primary" role="alert">
            Todo los campos son requeridos
          </div>';
  } else {
    $idcliente = $_POST['id'];
    $dni = $_POST['nit_ci'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $result = 0;
    if (is_numeric($dni)) {

      $query = mysqli_query($conexion, "SELECT * FROM cliente 
                                    where (nit_ci = '$dni'
                                    AND idcliente != $idcliente)");
      $result = mysqli_fetch_array($query);
      $resul = mysqli_num_rows($query);
    }

    if ($resul >= 1) {
      $alert = '<div class="alert alert-danger" role="alert">
                  El NIT o CI ya existe!!!
                </div>';
    } else {
      if ($dni == '') {
        $dni = 0;
      }
      $sql_update = mysqli_query($conexion, "UPDATE cliente 
                                            SET nit_ci = $dni, 
                                            nombre = '$nombre',
                                            telefono = '$telefono', 
                                            direccion = '$direccion' 
                                            WHERE idcliente = $idcliente");

      if ($sql_update) {
        $alert = '<div class="alert alert-success" role="alert">
                    Cliente actualizado correctamente
                  </div>';
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                  Error al actualizar el cliente!!!
                </div>';
      }
    }
  }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
  header("Location: lista_cliente.php");
}
$idcliente = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_cliente.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $idcliente = $data['idcliente'];
    $dni = $data['nit_ci'];
    $nombre = $data['nombre'];
    $telefono = $data['telefono'];
    $direccion = $data['direccion'];
  }
}
?>
<!-- Begin Page Content -->
  <div class="container-fluid pt-5 mt-5">
    <!-- Page breadcrumb -->	
<!-- 	  <nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	        	
        	<li class="breadcrumb-item"><a href="lista_cliente.php"><i class="fa fa-users text-info"></a></i></li>
            <li class="breadcrumb-item"><a href="editar_cliente.php"><i class="fa fa-edit text-info"></a></i></li>			
    		<li class="breadcrumb-item active" aria-current="page">Editar Cliente</li>
  		</ol>
  	</nav> -->
  	<!-- End Page breadcrumb -->

	<!-- Page Heading -->
	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">Editar Cliente</h1>
        		<?php if ($_SESSION['rol'] == 1) { ?>
				<a href="lista_cliente.php" class="btn btn-success btn-sm"><i class="fas fa-arrow-left mr-1"></i>regresar</a>
				<?php } ?>
      		</div>
    	</div>    
  <div class="card-body">
    <div class="row">
      <div class ="col-8 m-auto">
        <?php echo isset($alert) ? $alert : ''; ?>
      </div>        
      <form class="row col-10 m-auto p-3" action="" method="post" autocomplete="off">
        <!-- NIT / CI --> 
        <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
        <div class="col-md-6 mb-2">       
          <label for="nit_ci">NIT - CI</label>
          <input type="number" name="nit_ci" id="nit_ci" class="form-control form-control-sm" value="<?php echo $dni; ?>">
        </div>
        <div class="col-md-6 mb-2"> 
          <label for="nombre">Nombre / Razón Social</label>
          <input type="text" name="nombre" class="form-control form-control-sm" id="nombre" value="<?php echo $nombre; ?>">
        </div>
        <div class="col-md-6 mb-2"> 
          <label for="telefono">Teléfono</label>
          <input type="number"  name="telefono" class="form-control form-control-sm" id="telefono" value="<?php echo $telefono; ?>">
        </div>
        <div class="col-md-6 mb-4"> 
          <label for="direccion">Dirección</label>
          <input type="text"  name="direccion" class="form-control form-control-sm" id="direccion" value="<?php echo $direccion; ?>">
        </div>
        <div class="col-md-6 mb-4"> 
          <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Cliente</button>
        </div>
          
      </form>
    </div>
  

  </div>
  </div>
  <!-- /.container-fluid -->
  </div>
  </div>
  <!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>