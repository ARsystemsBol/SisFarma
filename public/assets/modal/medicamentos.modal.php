<?php
include("../../conexion.php");
session_start();
//print_r($_POST);
if (!empty($_POST)) {
  
// PRODECIMIENTOS MEDICAMENTOS

  // AGREGAR DETALLE TRASPASO DE ALMACEN A SUCURSAL
  if ($_POST['action'] == 'adddetalleventa') {
    if (empty($_POST['descripcion'])){
    echo 'error';
    }else {
    $idmedicamento = $_POST['idmedicamento'];
    $cantidad = $_POST['cantidad'];
    $descripcion = $_POST['descripcion'];
    $fecvencimiento = $_POST['fechavencimiento'];
    $precioventa = $_POST['preciounitario']; 
    $token = md5($_SESSION['idUser']);

    //aqui el reemplazo del codigo
    $query_insert_temp = mysqli_query($conexion, "INSERT INTO detalle_tempf(token_user, codmedicamento, fechavencimiento, 
                                                  cantidad, precio_venta, descripcion) 
                                                  VALUES ('$token', $idmedicamento, '$fecvencimiento', $cantidad, $precioventa, 
                                                  '$descripcion')");
    $query_consulta_temp = mysqli_query($conexion, "SELECT correlativo, codmedicamento, cantidad, fechavencimiento, 
                                                    precio_venta, descripcion
                                                    FROM detalle_tempf
                                                    WHERE token_user = '$token'");
    $result = mysqli_num_rows($query_consulta_temp);                                              
                                                 
    /* $query_detalle_temp = mysqli_query($conexion, "CALL add_detalle_temp_venta('$token', $idmedicamento, $cantidad, $precioventa, '$fecvencimiento','$descripcion')");
    $result = mysqli_num_rows($query_detalle_temp); */
   
    $detalleTabla = "";
    $sub_total = 0;
    $total = 0;
    $data = "";
    $arrayData = array();
    if ($result > 0) {
      while ($data = mysqli_fetch_assoc($query_consulta_temp)) {
        
        $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
        $sub_total = round($sub_total + $precioTotal, 2);
        $total = round($total + $precioTotal, 2);
        
        $detalleTabla .= '<tr class="aligin-middle">
            <td style="font-size:14px;" twidth="100px" class="text-center">'.$data['codmedicamento'].'</td>
            <td style="font-size:12px;" width="500px">'.$data['descripcion'].'</td>
            <td style="font-size:14px;" width="100px" class="text-center">'.$data['cantidad'].'</td>
            <td style="font-size:14px;" width="100px" class="text-center">'.$data['fechavencimiento'].'</td>
            <td style="font-size:14px;" width="100px" class="text-center">'.$data['precio_venta'].'</td>
            <td style="font-size:14px;" width="100px" class="text-center">'.$precioTotal.'</td>
            <td colspan="2" width="100px" class="text-center">
                <a href="#" class="btn btn-danger btn-sm"
                    onclick="event.preventDefault(); del_product_detalle_ventas('.$data['correlativo'].');"><i
                        class="fas fa-trash-alt"></i> </a>
            </td>
        </tr>';
        }
        $detalleTotales = '<tr>
            <td colspan="4"> </td>
            <td class="align-middle h5 textright text-rojo">Total Bs.</td>
            <td class="h5 textright text-verde">'.$total.'</td>
        </tr>';
        
        $arrayData['detalle'] = $detalleTabla;
        $arrayData['totales'] = $detalleTotales;
        echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
        }else {
        $data = 0;
        }
        mysqli_close($conexion);
    
    }
  exit;
  }

  // Anular VENTA
  if ($_POST['action'] == 'anularVentas') {
    $data = "";
    $token = md5($_SESSION['idUser']);
    $query_del = mysqli_query($conexion, "DELETE FROM detalle_tempf WHERE token_user = '$token'");
    mysqli_close($conexion);
    if ($query_del) {
    echo 'ok';
    }else {
    $data = 0;
    }
  exit;
  }

  // EXTRAE EL MEDICAMENTO SELECCIONADO DEL DETALLA TEMORAL VENTAS  
  if ($_POST['action'] == 'delDetalleVentasid') {
    if (empty($_POST['id_detalle'])){
    echo 'error';
    // code...
    }else {
    $id_detalle = $_POST['id_detalle'];
    $token = md5($_SESSION['idUser']);
    
    $query_detalle_tmp_st = mysqli_query($conexion, "CALL del_detalle_temp_ventas($id_detalle,'$token')");
    $result = mysqli_num_rows($query_detalle_tmp_st);
    
    $detalleTabla = '';
    $sub_total = 0;
    $total = 0;
    $data = "";
    $arrayData = array();
    if ($result > 0) {
    while ($data = mysqli_fetch_assoc($query_detalle_tmp_st)) {
    $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
    $sub_total = round($sub_total + $precioTotal, 2);
    $total = round($total + $precioTotal, 2);
    
    $detalleTabla .= '<tr class="aligin-middle">
        <td style="font-size:14px;" width="100px" class="text-center">'.$data['codmedicamento'].'</td>
        <td style="font-size:12px;" width="500px">'.$data['descripcion'].'</td>
        <td style="font-size:14px;" width="100px" class="text-center">'.$data['cantidad'].'</td>
        <td style="font-size:14px;" width="100px" class="text-center">'.$data['fechavencimiento'].'</td>
        <td style="font-size:14px;" width="100px" class="text-center">'.$data['precio_venta'].'</td>
        <td style="font-size:14px;" width="100px" class="text-center">'.$precioTotal.'</td>
        <td colspan="2" width="100px" class="text-center">
            <a href="#" class="btn btn-danger btn-sm"
                onclick="event.preventDefault(); del_product_detalle_ventas('.$data['correlativo'].');"><i
                    class="fas fa-trash-alt"></i> </a>
        </td>
    </tr>';
    }
    $detalleTotales = '<tr>
        <td colspan="3"> </td>
        <td class="align-middle h5 text-right text-rojo">Total Bs/.</td>
        <td class="h5 text-center text-verde">'.$total.'</td>
    </tr>';
    
    $arrayData['detalle'] = $detalleTabla;
    $arrayData['totales'] = $detalleTotales;
    echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
    }else {
    $data = 0;
    }
    mysqli_close($conexion);
    }
  exit;
  }

  // PROCESAR VENTA procesarVentas
  if ($_POST['action'] == 'procesarVentas') {
    if (empty($_POST['codcliente'])) {
      //code...
    }else{
      $codcliente = $_POST['codcliente'];

      $token = md5($_SESSION['idUser']);
      $usuario = $_SESSION['idUser'];

      $query = mysqli_query($conexion, "SELECT * FROM detalle_tempf WHERE token_user = '$token' ");
      $result = mysqli_num_rows($query);
    }

    if ($result > 0) {      
      $query_procesar = mysqli_query($conexion, "CALL procesar_ventas($usuario,$codcliente,'$token')");
      $result_detalle = mysqli_num_rows($query_procesar);
      if ($result_detalle > 0) {
        $data = mysqli_fetch_assoc($query_procesar);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
      }else {
        echo "error";
      }
    }else {
      echo "error";
    }
    mysqli_close($conexion);
    exit;
  }



}?>