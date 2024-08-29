<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) 
    || empty($_POST['correo']) 
    || empty($_POST['usuario']) 
    || empty($_POST['clave']) 
    || empty($_POST['rol'])) {
        $alert = '<div class="alert alert-danger" role="alert">
                    Todos los campos son obligatorios
                </div>';
    } else {

        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $user = $_POST['usuario'];
        $clave = md5($_POST['clave']);
        $idsuc = $_POST['sucursal'];
        $rol = $_POST['rol'];

        $query = mysqli_query($conexion, "SELECT * FROM usuario where correo = '$email'");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert = '<div class="alert alert-danger" role="alert">
                        El correo ya existe
                    </div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO usuario(nombre,correo,usuario,clave,rol, idsucursal)
                                                         values ('$nombre', '$email', '$user', '$clave', $rol, $idsuc)");
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
                            Usuario registrado
                        </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                        Error al registrar
                    </div>';
            }
        }
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

	<!-- Page breadcrumb -->	
<!-- 	<nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>	        	
        	<li class="breadcrumb-item"><a href="lista_usuarios.php"><i class="fa fa-users text-info"></a></i></li>
            <li class="breadcrumb-item"><i class="fas fa-users text-info mr-2"></i><i class="fas fa-save text-info"></i></li>						
    		<li class="breadcrumb-item active" aria-current="page">Registro de Usuario</li>
  		</ol>
  	</nav> -->
  	<!-- End Page breadcrumb -->

    <!-- Page Heading -->    
    <div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">Registro de Usuario</h1>       		
				<a href="lista_usuarios.php" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left fa-sm mr-2"></i>Regresar</a>				
      		</div>
    	</div> 
    <!-- Content Row -->   
    <div class="card-body"> 
        <div class="row">
            <div class ="col-8 m-auto">
                <?php echo isset($alert) ? $alert : ''; ?>
            </div>
            <form class="row col-10 m-auto p-1" action="" method="post" autocomplete="off">
           
                <!-- NOMBRE --> 
                <div class="col-md-6 mb-2">
                    <label for="nombre" class="form-label">NOMBRE DE USUARIO <span class="text-danger">*</span> </label>                    
                    <input autofocus type="text-capitalize" class="form-control form-control-sm"   name="nombre" id="nombre">
                </div>
                <!-- CORREO --> 
                <div class="col-md-6 mb-2">
                    <label for="correo" class="form-label">CORREO ELECTRONICO <span class="text-danger">*</span></label>
                    <input type="email" class="form-control form-control-sm"  name="correo" id="correo">
                </div>
                 <!-- SUCURSAL --> 
                <div class="col-md-4 mb-2">
                    <label for="sucursal" class="form-label">SUCURSAL</label>
                    <select name="sucursal" id="sucursal" class="form-control form-control-sm">
                    <option></option>
                            <?php
                            $query_rol = mysqli_query($conexion, "SELECT * FROM sucursal");
                            
                            $resultado_rol = mysqli_num_rows($query_rol);
                           
                            if ($resultado_rol > 0) {
                                while ($rol = mysqli_fetch_array($query_rol)) {
                            ?>
                                <option value="<?php echo $rol["idsucursal"]; ?>"><?php echo $rol["nombre"] ?></option>
                            <?php
                            }
                            }
                            ?>
                    </select>
                </div>                
                <!-- USUARIO --> 
                <div class="col-md-4 mb-2">
                    <label for="usuario" class="form-label">USUARIO<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="usuario" id="usuario">
                </div>
                <!-- CONTRASEÑA --> 
                <div class="col-md-4 mb-2">
                    <label for="clave" class="form-label">CONTRASEÑA<span class="text-danger">*</span></label>
                    <input type="password" class="form-control form-control-sm"  name="clave" id="clave">
                </div>
                <!-- ROL --> 
                <div class="col-md-4 mb-3">
                    <label for="rol" class="form-label">ROL</label>
                    <select name="rol" id="rol" class="form-control form-control-sm">
                            <?php
                            $query_rol = mysqli_query($conexion, "SELECT * FROM rol ORDER BY idrol ASC");
                            mysqli_close($conexion);
                            $resultado_rol = mysqli_num_rows($query_rol);
                            if ($resultado_rol > 0) {
                                while ($rol = mysqli_fetch_array($query_rol)) {
                            ?>
                                <option value="<?php echo $rol["idrol"]; ?>"><?php echo $rol["rol"] ?></option>
                            <?php
                            }
                            }
                            ?>
                    </select>
                </div>
                <div class="col-12">        
                    <input type="submit" value="Guardar Usuario" class="btn btn-primary btn-sm">
                    <a href="lista_usuarios.php" class="btn btn-danger btn-sm"></i>Cancelar</a>
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