 <?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombresuc']) || empty($_POST['direccionsuc']) || empty($_POST['telefonosuc']) || empty($_POST['ciudadsuc'])) {
      $alert = '<div class="alert alert-danger" role="alert">
                Todos los campos son obligatorios
              </div>';    
      
    } else {
      $idsucursal = $_GET['id'];
      $nombresuc = $_POST['nombresuc'];
      $direccionsuc = $_POST['direccionsuc'];
      $telefonosuc = $_POST['telefonosuc'];
      $ciudadsuc = $_POST['ciudadsuc'];     

      $query_insert = mysqli_query($conexion, "UPDATE sucursal SET nombre = '$nombresuc', direccion = '$direccionsuc' , telefono = '$telefonosuc', ciudad = '$ciudadsuc' 
                                              WHERE idsucursal = $idsucursal");
      if ($query_insert) {
        $alert = '<div class="alert alert-success" role="alert">
               Sucursal Actualizada
              </div>';
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                Error al actualizar
              </div>';
      }
    }
  }

  // Mostrar Datos

if (empty($_REQUEST['id'])) {
  header("Location: lista_sucursales.php");
}
$idsucursal = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM sucursal WHERE idsucursal = $idsucursal");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_sucursales.php");
} else {
  if ($data = mysqli_fetch_array($sql)) {
    $idsucursal = $data['idsucursal'];
    $nombre = $data['nombre'];
    $direccion = $data['direccion'];
    $telefono = $data['telefono'];
    $ciudad = $data['ciudad'];
  }
}
  ?>

 <!-- Begin Page Content -->
 <div class="container-fluid pt-5 mt-5">

<!--  <nav aria-label="breadcrumb">
  		<ol class="breadcrumb bg-azul">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-white"></a></i></li>
			  <li class="breadcrumb-item"><a href="lista_sucursales.php"><i class="fas fa-store text-white"></a></i></li>
    		<li class="breadcrumb-item"><a ><i class="fas fa-edit text-white"></a></i></li>
        <li class="breadcrumb-item active text-gray-500" aria-current="page"> Editar Sucursal</li>
  		</ol>
	</nav> -->	

	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between ">
        		<h1 class="h6 mb-0 text-uppercase">Editar Sucursal</h1>        		
				<a href="lista_sucursales.php" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left mr-3"></i>Ver Lista de Sucursales</a>				
      		</div>
    	</div>    
    <div class="card-body">
        <!-- Content Row -->   
     
   
    <div class ="col-8 m-auto">
      <?php echo isset($alert) ? $alert : ''; ?>
    </div>
  
    <form class="row col-8 m-auto" action="" method="post" autocomplete="off">
     
      <!-- NOMBRE SUCURSAL --> 
      <div class="col-md-8">
        <label for="nombresuc" class="form-label">NOMBRE - SUCURSAL</label>
        <input type="text" class="form-control form-control-sm" name = "nombresuc" id="nombresuc" value="<?php echo $nombre; ?>">
      </div>

      <!-- DIRECCION SUCURSAL--> 
      <div class="col-md-8 mb-2">
        <label for="direccionsuc" class="form-label">DIRECCION</label>
        <input type="text" class="form-control form-control-sm" name = "direccionsuc" id="direccionsuc" value="<?php echo $direccion; ?>">
      </div>       
      
      <!-- TELEFONO--> 
      <div class="col-4">
        <label for="telefonosuc" class="form-label">TELÉFONO</label>
        <input type="text" class="form-control form-control-sm"  name = "telefonosuc" id="telefonosuc" value="<?php echo $telefono; ?>">
      </div>
      
      <!-- CIUDAD--> 
      <div class="col-md-3 mb-3">
        <label for="ciudadsuc" class="form-label">CIUDAD</label>
        <input type="text" class="form-control form-control-sm"  name = "ciudadsuc" id="ciudadsuc" value="<?php echo $ciudad; ?>">
      </div> 
      
      <div class="col-12">
        <input type="submit" value="Editar Sucursal" class="btn btn-success">
       
   </form>
    </div> 
    </div>
   

   </div>
 </div>
 <!-- /.container-fluid -->

 </div>
 <!-- End of Main Content -->
 <?php include_once "includes/footer.php"; ?>