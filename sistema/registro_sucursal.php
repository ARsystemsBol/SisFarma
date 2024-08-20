 <?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombresuc']) || empty($_POST['direccionsuc']) || empty($_POST['telefonosuc']) || empty($_POST['ciudadsuc'])) {
      $alert = '<div class="alert alert-danger" role="alert">
                Todos los campos son obligatorios
              </div>';    
      
    } else {
      $nombresuc = $_POST['nombresuc'];
      $direccionsuc = $_POST['direccionsuc'];
      $telefonosuc = $_POST['telefonosuc'];
      $ciudadsuc = $_POST['ciudadsuc'];     
      $usuario = $_SESSION['idUser'];
      $query_insert = mysqli_query($conexion, "INSERT INTO sucursal(nombre, direccion, telefono, ciudad, usuario) 
                                              values ('$nombresuc', '$direccionsuc', 
                                              '$telefonosuc', '$ciudadsuc', $usuario)");
      if ($query_insert) {
        $alert = '<div class="alert alert-success" role="alert">
                Sucursal Registrada
              </div>';
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar la sucursal
              </div>';
      }
    }
  }
  ?>

 <!-- Begin Page Content -->
 <div class="container-fluid pt-5 mt-5">
  <!-- Page Heading -->	
<!-- 	<nav aria-label="breadcrumb">
  		<ol class="breadcrumb bg-azul">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-white"></a></i></li>
			  <li class="breadcrumb-item"><a href="lista_sucursales.php"><i class="fas fa-store text-white"></a></i></li>
    		<li class="breadcrumb-item"><a href="registro_sucursal.php"><i class="fas fa-save text-white"></a></i></li>
        <li class="breadcrumb-item active text-gray-500" aria-current="page"> Registro Sucursales</li>
  		</ol>
	</nav>	 -->

	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between ">
        		<h1 class="h6 mb-0 text-uppercase">Registro Sucursal</h1>        		
				    <a href="lista_sucursales.php" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left mr-3"></i>Ver Lista sucursales</a>				
      		</div>
    	</div>    
    <div class="card-body">
    <div class ="col-8 m-auto">
      <?php echo isset($alert) ? $alert : ''; ?>
    </div>
  
    <form class="row col-8 m-auto" action="" method="post" autocomplete="off">
     
      <!-- NOMBRE SUCURSAL --> 
      <div class="col-md-8">
        <label for="nombresuc" class="form-label">NOMBRE - SUCURSAL</label>
        <input type="text" class="form-control form-control-sm" name = "nombresuc" id="nombresuc">
      </div>

      <!-- DIRECCION SUCURSAL--> 
      <div class="col-md-8 mb-2">
        <label for="direccionsuc" class="form-label">DIRECCION</label>
        <input type="text" class="form-control form-control-sm" name = "direccionsuc" id="direccionsuc">
      </div>       
      
      <!-- TELEFONO--> 
      <div class="col-4">
        <label for="telefonosuc" class="form-label">TELÉFONO</label>
        <input type="text" class="form-control form-control-sm"  name = "telefonosuc" id="telefonosuc" placeholder="">
      </div>
      
      <!-- CIUDAD--> 
      <div class="col-md-3 mb-3">
        <label for="ciudadsuc" class="form-label">CIUDAD</label>
        <input type="text" class="form-control form-control-sm"  name = "ciudadsuc" id="ciudadsuc">
      </div> 
      
      <div class="col-12">
        <input type="submit" value="Registrar Sucursal" class="btn btn-success btn-sm">
       
   </form>
    </div>
    </div>   
    </div>
   <!-- Content Row -->        
   
    


 </div>
 <!-- /.container-fluid -->

 </div>
 <!-- End of Main Content -->
 <?php include_once "includes/footer.php"; ?>