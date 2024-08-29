<!-- SISTEMA/app/views/login.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SIS FARMA</title>

    <!-- Custom fonts for this template-->
    <link rel="shortcut icon" href="assets/img/farmacia2_favicon.png">
    <script src="https://kit.fontawesome.com/1ea8486269.js" crossorigin="anonymous"></script>
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="mt-2">  
    <div class="col-xl-4 m-auto text-center">
        <?php 
        session_start();
        if(isset($_SESSION['alert'])){
            echo $_SESSION['alert'];
            unset($_SESSION['alert']); 
        }
        ?>
    </div> 
    <div class="contenedor"> 
        <div class="header">
            <img src="assets/img/farmacia2_favicon.png" width="200px" height="200px">
        </div>
        <br>    
        <div class="main">
            <form method="POST" action="../app/controllers/LoginController.php">  
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
