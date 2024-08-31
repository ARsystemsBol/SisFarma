<?php
require_once "../../config/conexion.php";
require_once "../models/UserModel.php";

$alert = '';
session_start();

    if (!empty($_POST)) {
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $alert = '<div class="alert alert-danger" role="alert">
                        Ingrese su usuario y su clave
                    </div>';
                    // Redirige de vuelta al login con el mensaje de alerta
                $_SESSION['alert'] = $alert;
                header('Location: ../../public/');
                exit();
                session_destroy();
        } else {
            $userModel = new UserModel($conexion);
            $user = $_POST['usuario'];
            $clave = md5($_POST['clave']); // Encriptar la contraseña con MD5

            $dato = $userModel->getUser($user, $clave);

            if ($dato) {
                // Inicializar la sesión con los datos del usuario
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
                        header('location: ../views/dashboard.php');
                    } else {
                        header('location: ../views/dashboard.php');
                    }
                } else if ($_SESSION['estadocaja'] == 'ABIERTA') {
                    if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 2)) {
                        header('location: ../views/dashboard.php');
                    } else {
                        header('location: ../views/dashboard.php');
                    }
                }
                exit();
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                            Usuario o Contraseña Incorrecta
                        </div>';
                        $_SESSION['alert'] = $alert;
                        header('Location: ../../public/');
                        exit();
                session_destroy();
            }
        }
    }

require_once "../views/login.php";
