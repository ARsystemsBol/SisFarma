<?php
include "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['proveedor']) 
  || empty($_POST['nitprov']) 
  || empty($_POST['nombrecon']) 
  || empty($_POST['telefonocon']) 
  || empty($_POST['emailcon']) 
  || empty($_POST['direccioncon'])) {
    $alert = '<div class="alert alert-danger" role="alert">
                Los datos generales son obligatorios
              </div>';
  } else {
    $alert = $_POST['proveedor'];
    $idproveedor = $_GET['id'];
    $proveedor = $_POST['proveedor'];
    $lab = $_POST['lab'];
    $nitprov = $_POST['nitprov'];
    $nombrecon = $_POST['nombrecon'];
    $telefonocon = $_POST['telefonocon'];
    $emailcon = $_POST['emailcon'];
    $direccioncon = $_POST['direccioncon'];
    $cuentaprov = $_POST['cuentaprov'];
    $bancoprov = $_POST['bancoprov'];
    $tipocuentaprov = $_POST['tipocuentaprov'];

    $sql_update = mysqli_query($conexion, "UPDATE proveedor SET proveedor = '$proveedor', 
                                                                lab = '$lab',
                                                                nit = $nitprov , 
                                                                contacto = '$nombrecon', 
                                                                telefono = $telefonocon, 
                                                                mail = '$emailcon', 
                                                                direccion = '$direccioncon', 
                                                                nocuenta = '$cuentaprov', 
                                                                banco = '$bancoprov', 
                                                                tipocuenta = '$tipocuentaprov' 
                                                                WHERE codproveedor = $idproveedor");

    if ($sql_update) {
      $alert = '<div class="alert alert-success" role="alert"> Proveedor Actualizado </div>';
    } else {
      $alert = '<div class="alert alert-danger" role="alert"> Error al actualizar el proveedor </div>';
    }
  }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
  header("Location: lista_proveedor.php");
  mysqli_close($conexion);
}
$idproveedor = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM proveedor WHERE codproveedor = $idproveedor");
mysqli_close($conexion);
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_proveedor.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $idproveedor = $data['codproveedor'];
    $proveedor = $data['proveedor'];
    $lab = $data['lab'];
    $nitprov = $data['nit'];
    $nombrecon = $data['contacto'];
    $telefonocon = $data['telefono'];
    $emailcon = $data['mail'];
    $direccioncon = $data['direccion'];
    $cuentaprov = $data['nocuenta'];
    $bancoprov = $data['banco'];
    $tipocuentaprov = $data['tipocuenta'];
  }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

<!-- Page Heading -->	
<!-- <nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>
			<li class="breadcrumb-item"><a href="lista_proveedor.php"><i class="fas fa-building text-info"></a></i></li>
    		<li class="breadcrumb-item"><a ><i class="fas fa-edit text-info"></a></i></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Proveedor</li>
  		</ol>	
</nav> -->
	<!-- Page Heading -->
	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">editar proveedor</h1>        		
				<a href="lista_proveedor.php" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left mr-1"></i>Regresar</a>				
      		</div>
    	</div>    
    <div class="card-body">
          <!-- Content Row -->  
    <div class ="col-9 m-auto">
      <?php echo isset($alert) ? $alert : ''; ?>
    </div>
  
    <form class="row col-10 m-auto" action="" method="post" autocomplete="off">  

        <!-- sutitulo 1  - DATOS GENERALES--> 
        <div class="col-md-12 mb-2">
            <label class="h5 fw-bold text-verde mb-1">DATOS GENERALES</label>           
        </div>   

        <!-- NOMBRE PROVEEDOR --> 
        <div class="col-md-8 mb-2">
            <label for="proveedor" class="form-label">LABORATORIO</label>
            <input autofocus maxlength="55" type="text" name="proveedor" id="proveedor" class="form-control form-control-sm" value="<?php echo $proveedor; ?>">
        </div>

         <!-- NOMBRE CORTO  --> 
         <div class="col-md-4 mb-2">
            <label for="lab" class="form-label">NOMBRE CORTO</label>
            <input autofocus maxlength="30" type="text" name="lab" id="lab" class="form-control form-control-sm" value="<?php echo $lab; ?>">
        </div>
        <!-- NIT PROVEEDOR --> 
        <div class="col-md-8 mb-3">
            <label for="nitprov" class="form-label">NIT</label>
            <input type="number" name="nitprov" id="nitprov" class="form-control form-control-sm" value="<?php echo $nitprov; ?>">
        </div>
           <!-- sutitulo 1  - DATOS GENERALES--> 
           <div class="col-md-12 mb-2">
            <label class="h5 fw-bold text-azul mb-1">DATOS CONTACTO </label>           
        </div> 
        <!-- NOMBRE CONTACTO--> 
        <div class="col-md-8 mb-2">
            <label for="nombrecon" class="form-label">NOMBRE CONTACTO</label>
            <input type="text" name="nombrecon" id="nombrecon" class="form-control form-control-sm"  value="<?php echo $nombrecon; ?>">
        </div>
        <!-- TELEFONO CONTACTO--> 
        <div class="col-md-4 mb-2">
            <label for="telefonocon" class="form-label">TELÉFONO CONTACTO</label>
            <input type="number" name="telefonocon" id="telefonocon" class="form-control form-control-sm"  value="<?php echo $telefonocon; ?>">
        </div>
        <!-- MAIL CONTACTO--> 
        <div class="col-md-5 mb-2">
            <label for="emailcon" class="form-label">CORREO ELECTÓNICO</label>
            <input type="mail" name="emailcon" id="emailcon" class="form-control form-control-sm" value="<?php echo $emailcon; ?>">
        </div>
        <!-- DIRECCION CONTACTO--> 
        <div class="col-md-7 mb-3">
            <label for="direccioncon" class="form-label">DIRECCION</label>
            <input maxlength="45" type="text"  name="direccioncon" id="direccioncon" class="form-control form-control-sm" value="<?php echo $direccioncon; ?>">
        </div>

        <!-- sutitulo  - DATOS BANCARIOS--> 
        <div class="col-md-12 mb-2">
            <label class="h5 fw-bold text-rojo mb-1">DATOS BANCARIOS</label>           
        </div>
        
        <!-- NRO. CUENTA--> 
        <div class="col-md-3 mb-2">
            <label for="cuentaprov" class="form-label">No. CUENTA</label>
            <input type="text"  name="cuentaprov" id="cuentaprov" class="form-control form-control-sm" value="<?php echo $cuentaprov; ?>">
        </div>
        <!-- BANCO--> 
        <div class="col-md-6 mb-2">
            <label for="bancoprov" class="form-label">NOMBRE BANCO</label>
            <input type="text"  name="bancoprov" id="bancoprov" class="form-control form-control-sm" value="<?php echo $bancoprov; ?>">
        </div>
        <!-- TIPO DE CUENTA--> 
        <div class="col-md-3 mb-4">
            <label for="tipocuentaprov" class="form-label">TIPO DE CUENTA</label>
            <input type="text"  name="tipocuentaprov" id="tipocuentaprov" class="form-control form-control-sm" value="<?php echo $tipocuentaprov; ?>">
        </div>
        <!-- botones-->       
        <div class="col-120 ">
            <input type="submit" value="Actualizar Proveedor" class="btn btn-primary ml-3">
            <a href="lista_proveedor.php" class="btn btn-danger ml-3">Regresar</a>            
        </div>
   </form>
  </div>
</div>

 
  


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>