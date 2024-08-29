<?php
// SISTEMA/app/controllers/LoginController.php

session_start();
require_once '../../config/conexion.php';

$alert = '';

if (!empty($_SESSION['active'])) {
    header('location: /sistema/public/');
    exit();
}

if (!empty($_POST)) {
    if (empty($_POST['usuario']) || empty($_POST['clave'])) {
        $alert = '<div class="alert alert-danger" role="alert">
                    Ingrese su usuario y su clave
                  </div>';
    } else {
        $user = $_POST['usuario'];
        $clave = md5($_POST['clave']); // Encriptar la contraseña con MD5

        // Preparar la consulta SQL para prevenir inyecciones SQL
        $query = $conexion->prepare("SELECT u.idusuario, u.nombre, u.correo, u.usuario, u.idsucursal AS idsuc,
        r.idrol, r.rol , s.nombre AS dessucursal, c.nombrecaja, c.id AS idcaja, s.almacen,
        ec.descripcion AS descaja
        FROM usuario u 
        INNER JOIN rol r ON u.rol = r.idrol 
        INNER JOIN sucursal s ON u.idsucursal = s.idsucursal
        INNER JOIN caja c ON  u.idusuario = c.idusuario
        INNER JOIN estado_caja ec ON c.estado = ec.id 
        WHERE u.usuario = ? AND u.clave = ?");

        // Vincular parámetros
        $query->bind_param("ss", $user, $clave);
        $query->execute();

        // Obtener el resultado
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $dato = $result->fetch_assoc();
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

            // Redirigir según el estado de la caja y el rol
            if ($_SESSION['estadocaja'] == 'CERRADA') {
                if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 2)) {
                    header('location: /sistema/public/');
                } else {
                    header('location: /sistema/public/a_caja.php');
                }
            } else if ($_SESSION['estadocaja'] == 'ABIERTA') {
                if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 2)) {
                    header('location: /sistema/public/');
                } else {
                    header('location: /sistema/public/registro_ventas.php');
                }
            }
            exit();
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                        Usuario o Contraseña Incorrecta
                      </div>';
            session_destroy();
        }

        $query->close();
        mysqli_close($conexion);
    }
}
