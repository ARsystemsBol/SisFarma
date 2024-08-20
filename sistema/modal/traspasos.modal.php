<?php
include("../../conexion.php");
session_start();
//print_r($_POST);
if (!empty($_POST)) {

    
//****************************************************** */
// TRASPASOS */
//****************************************************** */

// AGREGAR DETALLE TRASPASO DE ALMACEN A SUCURSAL
if ($_POST['action'] == 'addDetalle-n1') {
  if (empty($_POST['descripcion'])){
  echo 'error';
  }else {
  $idmedicamento = $_POST['idmedicamento'];
  $cantidad = $_POST['cantidad'];
  $descripcion = $_POST['descripcion'];
  $fecvencimiento = $_POST['fecvencimiento'];
  $precioventa = $_POST['precioventa'];
  $preciocompra = $_POST['preciocompra'];
  $token = md5($_SESSION['idUser']);
  
  $query_detalle_temp = mysqli_query($conexion, "CALL add_detalle_temp_traspaso('$token', $idmedicamento, $cantidad,
  $preciocompra, $precioventa, '$fecvencimiento','$descripcion')");
  $result = mysqli_num_rows($query_detalle_temp);
  
  $detalleTabla = '';
  $sub_total = 0;
  $total = 0;
  $data = "";
  $arrayData = array();
  if ($result > 0) {
  while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
  $precioTotal = round($data['cantidad'] * $data['precioventa'], 2);
  $sub_total = round($sub_total + $precioTotal, 2);
  $total = round($total + $precioTotal, 2);
  
  $detalleTabla .= '<tr>
      <td width="100px" class="text-center">'.$data['idmedicamento'].'</td>
      <td width="500px">'.$data['nombrecompleto'].'</td>
      <td width="100px" class="text-center">'.$data['cantidad'].'</td>
      <td width="100px" class="text-center">'.$data['fechavencimiento'].'</td>
      <td width="100px" class="text-center">'.$data['precioventa'].'</td>
      <td width="100px" class="text-center">'.$precioTotal.'</td>
      <td width="100px" class="text-center">
          <a href="#" class="btn btn-danger btn-sm"
              onclick="event.preventDefault(); del_product_detalle_st('.$data['correlativo'].');"><i
                  class="fas fa-trash-alt"></i> Eliminar</a>
      </td>
  </tr>';
  }
  $detalleTotales = '<tr>
      <td colspan="1" class="align-middle h6 textright text-danger">Total Bs/.</td>
      <td class="h5 textright text-success">'.$total.'</td>
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
  
// EXTRAE EL MEDICAMENTO SELECCIONADO DEL DETALLA TEMORAL
  // TRASPASO ALMACEN A SUCURSAL delDetalleSt
  // extrae datos del detalle temp *4 delProductoDetalle
if ($_POST['action'] == 'delDetallest') {
  if (empty($_POST['id_detalle'])){
  echo 'error';
  // code...
  }else {
  $id_detalle = $_POST['id_detalle'];
  $token = md5($_SESSION['idUser']);
  
  $query_detalle_tmp_st = mysqli_query($conexion, "CALL del_detalle_temp_st($id_detalle,'$token')");
  $result = mysqli_num_rows($query_detalle_tmp_st);
  
  $detalleTabla = '';
  $sub_total = 0;
  $total = 0;
  $data = "";
  $arrayData = array();
  if ($result > 0) {
  while ($data = mysqli_fetch_assoc($query_detalle_tmp_st)) {
  $precioTotal = round($data['cantidad'] * $data['precioventa'], 2);
  $sub_total = round($sub_total + $precioTotal, 2);
  $total = round($total + $precioTotal, 2);
  
  $detalleTabla .= '<tr>
      <td width="100px" class="text-center">'.$data['idmedicamento'].'</td>
      <td width="500px">'.$data['nombrecompleto'].'</td>
      <td width="100px" class="text-center">'.$data['cantidad'].'</td>
      <td width="100px" class="text-center">'.$data['fechavencimiento'].'</td>
      <td width="100px" class="text-center">'.$data['precioventa'].'</td>
      <td width="100px" class="text-center">'.$precioTotal.'</td>
      <td width="100px" class="text-center">
          <a href="#" class="btn btn-danger btn-sm"
              onclick="event.preventDefault(); del_product_detalle_st('.$data['correlativo'].');"><i
                  class="fas fa-trash-alt"></i> Eliminar</a>
      </td>
  </tr>';
  }
  $detalleTotales = '<tr>
      <td colspan="1" class="align-middle h6 textright text-danger">Total Bs/.</td>
      <td class="h5 textright text-success">'.$total.'</td>
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
  
  
// Anular TRASPASO
if ($_POST['action'] == 'anularTraspaso') {
  $data = "";
  $token = md5($_SESSION['idUser']);
  $query_del = mysqli_query($conexion, "DELETE FROM detalle_temp_traspaso WHERE token_user = '$token'");
  mysqli_close($conexion);
  if ($query_del) {
  echo 'ok';
  }else {
  $data = 0;
  }
exit;
}

//PROCESAR TRASPASO A SUCURSAL btn_procesar-n1
if ($_POST['action'] == 'procesarTraspaso-n1') {
  if (empty($_POST['des_n1'])) {   
    
  }else{
    $tipot_n1 = $_POST['tipot_n1'];
    $suco_n1 = $_POST['suco_n1'];
    $sucd_n1 = $_POST['sucd_n1'];
    $idp_n1 = $_POST['idp_n1'];
    $des_n1 = $_POST['des_n1'];

    $token = md5($_SESSION['idUser']);
    $usuario = $_SESSION['idUser'];
    $query_1 = mysqli_query($conexion, "SELECT * FROM detalle_temp_traspaso WHERE token_user = '$token' ");
    $result = mysqli_num_rows($query_1);
  }
  if ($result > 0) {
    $query_ptn1 = mysqli_query($conexion, "CALL procesar_traspason1($usuario,'$token',$tipot_n1, $suco_n1, $sucd_n1,$idp_n1,'$des_n1')");
    $result_detalle = mysqli_num_rows($query_ptn1);
    if ($result_detalle > 0) {
      $data = mysqli_fetch_assoc($query_ptn1);
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

// PROCESAR DEVOLUCION A ALMACEN CENTRAL
if ($_POST['action'] == 'procesarTraspaso-n2') {
  if (empty($_POST['des_n1'])) {   
    
  }else{
    $tipot_n1 = $_POST['tipot_n1'];
    $suco_n1 = $_POST['suco_n1'];
    $sucd_n1 = $_POST['sucd_n1'];   
    $des_n1 = $_POST['des_n1'];

    $token = md5($_SESSION['idUser']);
    $usuario = $_SESSION['idUser'];
    $query_1 = mysqli_query($conexion, "SELECT * FROM detalle_temp_traspaso WHERE token_user = '$token' ");
    $result = mysqli_num_rows($query_1);
  }
  if ($result > 0) {
    $query_ptn1 = mysqli_query($conexion, "CALL procesar_traspason2($usuario,'$token',$tipot_n1, $suco_n1, $sucd_n1,'$des_n1')");
    $result_detalle = mysqli_num_rows($query_ptn1);
    if ($result_detalle > 0) {
      $data = mysqli_fetch_assoc($query_ptn1);
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

  









  exit;
}
?>