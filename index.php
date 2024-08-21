<?php
$alert = '';
session_start();
if (!empty($_SESSION['active'])) {
  header('location: sistema/');
 } else {
  if (!empty($_POST)) {
    if (empty($_POST['usuario']) 
    || empty($_POST['clave'])) {
      $alert = '<div class="alert alert-danger" role="alert">
                Ingrese su usuario y su clave
                </div>';
    } else {
      require_once "conexion.php";
      $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
      $clave = md5(mysqli_real_escape_string($conexion, $_POST['clave']));
      $query = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, u.idsucursal AS idsuc,
                                        r.idrol, r.rol , s.nombre AS dessucursal, c.nombrecaja, c.id AS idcaja, s.almacen,
                                        ec.descripcion AS descaja, c.id
                                        FROM usuario u 
                                        INNER JOIN rol r ON u.rol = r.idrol 
                                        INNER JOIN sucursal s ON u.idsucursal = s.idsucursal
                                        INNER JOIN caja c ON  u.idusuario = c.idusuario
                                        INNER JOIN estado_caja ec ON c.estado = ec.id 
                                        WHERE u.usuario = '$user' 
                                        AND u.clave = '$clave'");
      mysqli_close($conexion);
      $resultado = mysqli_num_rows($query);
      if ($resultado > 0) {
        $dato = mysqli_fetch_array($query);
        $_SESSION['active'] = true;
        $_SESSION['idUser'] = $dato['idusuario'];
        $_SESSION['nombre'] = $dato['nombre'];
        $_SESSION['email'] = $dato['correo'];
        $_SESSION['user'] = $dato['usuario'];
        $_SESSION['rol'] = $dato['idrol'];
        $_SESSION['rol_name'] = $dato['rol'];
        $_SESSION['sucursal'] = $dato['dessucursal'];
        $_SESSION['idsuc'] = $dato['idsuc'];
        $_SESSION['caja'] = $dato['nombrecaja'];
        $_SESSION['idcaja'] = $dato['idcaja']; 
        $_SESSION['almacen'] = $dato['almacen']; 
        $_SESSION['estadocaja'] = $dato['descaja']; 
        
        if ($_SESSION['estadocaja'] == 'CERRADA') {
          if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 2)) {
            header('location: sistema/');
          } else {
            header('location: sistema/a_caja.php');
          }
          
        } else if($_SESSION['estadocaja'] == 'ABIERTA') {
          if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 2)) {
            header('location: sistema/');
          } else {
            header('location: sistema/registro_ventas.php');
          }
        }
        
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
                  Usuario o Contraseña Incorrecta
                  </div>';
        session_destroy();
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SIS FARMA</title>

  <!-- Custom fonts for this template-->
  <link rel="shortcut icon" href="sistema/img/farmacia2_favicon.png">
  <!-- <link href="sistema/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"> -->
  <!-- Custom styles for this template-->
  <script src="https://kit.fontawesome.com/1ea8486269.js" crossorigin="anonymous"></script>
  <!-- <link href="sistema/css/estilologin.css" rel="stylesheet"> -->
  <link href="sistema/css/style.css" rel="stylesheet">
  <link href="sistema/css/sb-admin-2.min.css" rel="stylesheet">
 

</head>

<body class="mt-2">
  <div class="col-xl-4 m-auto text-center">
    <?php echo isset($alert) ? $alert : ""; ?>
  </div>
 <div class="contenedor"> 
 	<div class="header">
    <img src="sistema/img/farmacia2_favicon.png" width="200px" height="200px" >
 	</div>
  <br>    
 	<div class="main">
   <form method="POST">  
 			<span>
 				<i class="fa fa-user"></i>
 				<input type="text" placeholder="Usuario" name="usuario">
 			</span><br>
 			<span>
 				<i class="fa fa-lock"></i>
 				<input type="password" placeholder="Contraseña" name="clave">
 			</span><br>       
        <button type="submit">INGRESAR</button>
 		</form>
 	</div>
 </div>
</body>
</html>