<?php
include "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['nombre']) 
  || empty($_POST['correo']) 
  || empty($_POST['sucursal'])
  || empty($_POST['usuario']) 
  || empty($_POST['rol'])) {
    $alert = '<p class"error">Todo los campos son requeridos</p>';
  } else {
    $idusuario = $_GET['id'];

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $sucursal = $_POST['sucursal'];
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];

    $sql_update = mysqli_query($conexion, "UPDATE usuario 
                                          SET nombre = '$nombre', correo = '$correo' ,
                                          idsucursal = $sucursal, usuario = '$usuario', rol = $rol 
                                          WHERE idusuario = $idusuario");
    $alert = '<div class="alert alert-success" role="alert">
              Usuario Actualizado
              </div>';
  }
}

// Mostrar Datos

if (empty($_REQUEST['id'])) {
  header("Location: lista_usuarios.php");
}
$idusuario = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.correo, u.rol, u.idsucursal, u.usuario,
                                r.rol AS desrol, s.nombre AS dessucursal
                                FROM usuario u
                                INNER JOIN rol r ON u.rol = r.idrol
                                INNER JOIN sucursal s ON u.idsucursal = s.idsucursal
                                WHERE idusuario = $idusuario");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_usuarios.php");
} else {
  if ($data = mysqli_fetch_array($sql)) {    
    $nombre = $data['nombre'];
    $correo = $data['correo'];    
    $idsuc = $data['idsucursal'];
    $sucursal = $data['dessucursal'];
    $usuario = $data['usuario'];
    $idrol = $data['rol'];
    $desrol = $data['desrol'];
  }
}
?>


<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

    <!-- Page breadcrumb -->
    <!--    <nav aria-label="breadcrumb">
  		<ol class="breadcrumb bg-azul">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-white"></a></i></li>	        	
        	<li class="breadcrumb-item"><a href="lista_usuarios.php"><i class="fa fa-users text-white"></a></i></li>
          <li class="breadcrumb-item"><i class="fas fa-users text-white mr-2"></i><i class="fas fa-edit text-white"></i></li>						
    		<li class="breadcrumb-item active text-gray-500" aria-current="page">Editar Usuario</li>
  		</ol>
  	</nav> -->
    <!-- End Page breadcrumb -->

    <!-- Page Heading -->
    <div class="card">
        <div class="card-header bg-azul text-white ">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h6 mb-0 text-uppercase">Editar Usuario</h1>
                <a href="lista_usuarios.php" class="btn btn-danger btn-sm"><i
                        class="fas fa-arrow-left mr-2"></i>Regresar</a>
            </div>
        </div>
        <!-- Content Row -->
        <div class="card-body">
            <div class="row">               
                  <div class ="col-8 m-auto">
                    <?php echo isset($alert) ? $alert : ''; ?>
                  </div>
                  <form class="row col-8 m-auto p-1" action="" method="post" autocomplete="off">
                    <input type="hidden" name="id" value="<?php echo $idusuario; ?>">
                    <!-- NOMBRE --> 
                    <div class="col-md-6 mb-2">
                        <label for="nombre" class="form-label">NOMBRE DE USUARIO <span class="text-danger">*</span> </label>                    
                        <input autofocus type="text" placeholder="Ingrese nombre" class="form-control form-control-sm" name="nombre" id="nombre" value="<?php echo $nombre; ?>">
                    </div>
                     <!-- CORREO --> 
                    <div class="col-md-6 mb-2">
                        <label for="correo" class="form-label">CORREO <span class="text-danger"> *</span></label>
                        <input type="email" class="form-control form-control-sm" name="correo" id="correo" value="<?php echo $correo; ?>">
                    </div>                    
                    <!-- SUCURSAL --> 
                    <div class="col-md-4 mb-2">
                        <label for="sucursal" class="form-label">SUCURSAL</label>
                        <select name="sucursal" id="sucursal" class="form-control form-control-sm">
                        <option value="<?php echo $idsuc; ?>"><?php echo $sucursal; ?></option>
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
                        <input type="text-capitalize" class="form-control form-control-sm" name="usuario" id="usuario" value="<?php echo $usuario; ?>">
                    </div>
                    <!-- ROL --> 
                    <div class="col-md-4 mb-3">
                        <label for="rol" class="form-label">ROL</label>
                        <select name="rol" id="rol" class="form-control form-control-sm">
                        <option value="<?php echo $idrol; ?>"><?php echo $desrol; ?></option>
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
                     <!-- BOTON --> 
                    <div class="col-12">        
                    <button type="submit" class="btn btn-danger "><i class="fas fa-user-edit mr-2"></i> Editar Usuario</button>
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