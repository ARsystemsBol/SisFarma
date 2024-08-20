<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) 
    || empty($_POST['direccion'])) {
        $alert = '<div class="alert alert-danger" role="alert">
                    Complete los campos opbligatorios
                 </div>';
    } else {
        $dni = $_POST['nit_ci'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $usuario_id = $_SESSION['idUser'];

        $result = 0;
        if (is_numeric($dni)) {
            $query = mysqli_query($conexion, "SELECT * FROM cliente where nit_ci = '$dni'");
            $result = mysqli_fetch_array($query);
        }
        if ($result > 0) {
            $alert = '<div class="alert alert-danger" role="alert">
                            El NIT o CI ya existe !!!
                        </div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO cliente(nit_ci,nombre,telefono,
                                                    direccion, usuario_id) 
                                                    values ('$dni', '$nombre', '$telefono', 
                                                    '$direccion', '$usuario_id')");
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
                                Cliente Registrado
                            </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                                Error al Guardar
                        </div>';
            }
        }
    }
    mysqli_close($conexion);
}
?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">
    <!-- Page breadcrumb -->	
<!-- 	<nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	        	
        	<li class="breadcrumb-item"><a href="lista_cliente.php"><i class="fa fa-users text-info"></a></i></li>
            <li class="breadcrumb-item"><a href="registro_cliente.php"><i class="fa fa-save text-info"></a></i></li>			
    		<li class="breadcrumb-item active" aria-current="page">Registro Cliente</li>
  		</ol>
  	</nav> -->
  	<!-- End Page breadcrumb -->

	<!-- Page Heading -->
	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">Registro Cliente</h1>
        		<?php if ($_SESSION['rol'] == 1) { ?>
				<a href="lista_cliente.php" class="btn btn-success btn-sm"><i class="fas fa-arrow-left mr-1"></i>regresar</a>
				<?php } ?>
      		</div>
    	</div>    
    <div class="card-body">
         <!-- Content Row -->
    <div class="row">
        <div class ="col-8 m-auto">
            <?php echo isset($alert) ? $alert : ''; ?>
        </div>        
        <form class="row col-10 m-auto p-3" action="" method="post" autocomplete="off">
            <!-- NIT / CI --> 
            <div class="col-md-6 mb-2">
                <label for="nit_ci" class="form-label">NIT / CI</label>                    
                <input type="number" name="nit_ci" id="nit_ci" class="form-control form-control-sm">
            </div>
            <!-- NOMBRE CLIENTE -->  
            <div class="col-md-6 mb-2">
                <label for="nombre">Nombre / Razon Social</label>
                <input type="text" name="nombre" id="nombre" class="form-control form-control-sm">
            </div>
            <!-- TELEFONO -->  
            <div class="col-md-6 mb-2">
                <label for="telefono">Teléfono</label>
                <input type="number"  name="telefono" id="telefono" class="form-control form-control-sm">
            </div>
            <!-- DIRECCION -->  
            <div class="col-md-6 mb-4">
                <label for="direccion">Dirección</label>
                <input type="text" value="SIN DIRECCION" name="direccion" id="direccion" class="form-control form-control-sm">
            </div>
            <div class="col-md-6 mb-4">
                <input type="submit" value="Guardar Cliente" class="btn btn-primary">
            </div>               
        </form>
    </div>
    </div>  
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>