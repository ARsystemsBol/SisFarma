<?php
include_once "includes/header.php";
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
        $proveedor = $_POST['proveedor'];
        $lab =$_POST['lab'];
        $nitprov = $_POST['nitprov'];
        $nombrecon = $_POST['nombrecon'];
        $telefonocon = $_POST['telefonocon'];
        $emailcon = $_POST['emailcon'];
        $direccioncon = $_POST['direccioncon'];
        $cuentaprov = $_POST['cuentaprov'];
        $bancoprov = $_POST['bancoprov'];
        $tipocuentaprov = $_POST['tipocuentaprov'];
        $usuario_id = $_SESSION['idUser'];
        $query = mysqli_query($conexion, "SELECT * FROM proveedor where nit = $nitprov");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert = '<div class="alert alert-danger" role="alert">
                        El NIT ya esta registrado
                    </div>';
        }else{
        

        $query_insert = mysqli_query($conexion, "INSERT INTO proveedor(proveedor,lab, nit,contacto,telefono,mail,
                                                direccion,nocuenta,banco,tipocuenta,usuario_id) 
                                                VALUES ('$proveedor', '$lab', $nitprov,'$nombrecon','$telefonocon',
                                                '$emailcon', '$direccioncon', '$cuentaprov', '$bancoprov',
                                                '$tipocuentaprov', $usuario_id)");
        if ($query_insert) {
            $alert = '<div class="alert alert-success" role="alert">
                        Proveedor Registrado
                    </div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                       Error al registrar proveedor
                    </div>';
                    
        }
        }
    }
}
mysqli_close($conexion);
?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">
	<!-- Breadcrumb -->	
	<!-- <nav aria-label="breadcrumb">
  		<ol class="breadcrumb bg-azul">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-white"></a></i></li>
			<li class="breadcrumb-item"><a href="lista_proveedor.php"><i class="fas fa-building text-white"></a></i></li>
    		<li class="breadcrumb-item"><a href="registro_proveedor.php"><i class="fas fa-vial text-white"></a></i></li>
            <li class="breadcrumb-item active text-gray-500" aria-current="page">Registro Proveedor</li>
  		</ol>
    </nav> -->

	<!-- Page Heading -->
	<div class="card"> 
        <!-- Encabezado tarjeta -->
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">registro proveedor</h1>        		
				<a href="lista_proveedor.php" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left mr-1"></i>Regresar</a>				
      		</div>
    	</div>
        <!-- Cuarpo tarjeta -->    
        <div class="card-body">
            <!-- Content Row -->  
            <div class ="col-9 m-auto">
                <?php echo isset($alert) ? $alert : ''; ?>
            </div>  
            <form class="row col-8 m-auto" action="" method="post" autocomplete="off">  

                    <!-- sutitulo 1  - DATOS GENERALES--> 
                    <div class="col-md-12 mb-2">
                        <label class="h5 fw-bold text-verde mb-1">DATOS GENERALES</label>           
                    </div>   

                    <!-- NOMBRE PROVEEDOR --> 
                    <div class="col-md-8 mb-2">
                        <label for="proveedor" class="form-label">PROVEEDOR</label>
                        <input autofocus maxlength="55" type="text" name="proveedor" id="proveedor" class="form-control form-control-sm">
                    </div>
                     <!-- NOMBRE CORTO --> 
                     <div class="col-md-4 mb-2">
                        <label for="lab" class="form-label">NOMBRE CORTO</label>
                        <input maxlength="30" type="text" name="lab" id="lab" class="form-control form-control-sm">
                    </div>
                    <!-- NIT PROVEEDOR --> 
                    <div class="col-md-8 mb-3">
                        <label for="nitprov" class="form-label">NIT</label>
                        <input type="number" name="nitprov" id="nitprov" class="form-control form-control-sm">
                    </div>
                    <!-- sutitulo  - DATOS CONTACTO--> 
                    <div class="col-md-12 mb-2">
                        <label class="h5 fw-bold text-azul mb-1">DATOS CONTACTO</label>           
                    </div>
                    <!-- NOMBRE CONTACTO--> 
                    <div class="col-md-8 mb-2">
                        <label for="nombrecon" class="form-label">NOMBRE CONTACTO</label>
                        <input maxlength="50" type="text" name="nombrecon" id="nombrecon" class="form-control form-control-sm">
                    </div>
                    <!-- TELEFONO CONTACTO--> 
                    <div class="col-md-4 mb-2">
                        <label for="telefonocon" class="form-label">TELÉFONO CONTACTO</label>
                        <input type="number" name="telefonocon" id="telefonocon" class="form-control form-control-sm">
                    </div>
                    <!-- MAIL CONTACTO--> 
                    <div class="col-md-5 mb-2">
                        <label for="emailcon" class="form-label">CORREO ELECTÓNICO</label>
                        <input maxlength="35" type="mail" name="emailcon" id="emailcon" class="form-control form-control-sm">
                    </div>
                    <!-- DIRECCION CONTACTO--> 
                    <div class="col-md-7 mb-3">
                        <label for="direccioncon" class="form-label">DIRECCION</label>
                        <input maxlength="75" type="text"  name="direccioncon" id="direccioncon" class="form-control form-control-sm">
                    </div>

                    <!-- sutitulo  - DATOS BANCARIOS--> 
                    <div class="col-md-12 mb-2">
                        <label class="h5 fw-bold text-rojo mb-1">DATOS BANCARIOS</label>           
                    </div>

                    <!-- NRO. CUENTA--> 
                    <div class="col-md-3 mb-2">
                        <label for="cuentaprov" class="form-label">No. CUENTA</label>
                        <input type="text"  name="cuentaprov" id="cuentaprov" class="form-control form-control-sm">
                    </div>
                    <!-- BANCO--> 
                    <div class="col-md-6 mb-2">
                        <label for="bancoprov" class="form-label">NOMBRE BANCO</label>
                        <input maxlength="50" type="text"  name="bancoprov" id="bancoprov" class="form-control form-control-sm">
                    </div>
                    <!-- TIPO DE CUENTA--> 
                    <div class="col-md-3 mb-4">
                        <label for="tipocuentaprov" class="form-label">TIPO DE CUENTA</label>
                        <input type="text"  name="tipocuentaprov" id="tipocuentaprov" class="form-control form-control-sm">
                    </div>
                    <!-- botones-->       
                    <div class="col-md-12 ">
                        <input type="submit" value="Guardar Proveedor" class="btn btn-success">
                        <a href="lista_proveedor.php" class="btn btn-danger">Regresar</a>            
                    </div>
            </form>
    </div> 
</div> 
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>