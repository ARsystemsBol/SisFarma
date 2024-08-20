<?php
include("../../conexion.php");
session_start();
//print_r($_POST);
if (!empty($_POST)) {

    // Procesde de verificacion de caja abierta    exampleModal    
    if ($_POST['action'] == 'vercaja') {
        if (empty($_POST['action'])) {   
        //code... 
        }else{
        
        $token = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];
        
        $query = mysqli_query($conexion, "SELECT c.estado, ec.descripcion
                                            FROM  caja c 
                                            INNER JOIN estado_caja ec ON estado = ec.id 
                                            WHERE c.idusuario = $usuario");
        $result = mysqli_num_rows($query);
        }
        if ($result > 0) { 
            $datosestado = mysqli_fetch_array($query);      
            echo json_encode($datosestado,JSON_UNESCAPED_UNICODE);        
            
        mysqli_close($conexion);
        exit;
        }
        exit;
    }


    // PROCEDIMIENTO CERRAR CAJA
    if ($_POST['action'] == 'cerrarCaja') {
        if (empty($_POST['observaciones'])) {   
        //code... 
        }else{
        
        $diferencia = $_POST['diferencia'];
       
        $observaciones = $_POST['observaciones'];
        $efectivocaja = $_POST['efectivocaja'];        
        
      
        $usuario = $_SESSION['idUser'];    
        $idcaja=  $_SESSION['idcaja'];
        $query_update = mysqli_query($conexion, "UPDATE cajas 
                                                SET efectivo = $efectivocaja,
                                                    diferencia = $diferencia,
                                                    observaciones = '$observaciones',
                                                    idestado = 2
                                                WHERE idcaja = $idcaja"); 


        $query_update_2 = mysqli_query($conexion, "UPDATE caja
                                                SET estado = 2                                                                                                 
                                                WHERE idusuario = $usuario");        
                                                mysqli_close($conexion); 
        }
        if ($query_update_2) {
            
            $data = 12;
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        } else {
            $data = 0;
        }
        
    }else {
    $data = 0;
    exit;
    
    }
   

 

}

?>