<?php
include("../conexion.php");
session_start();
//print_r($_POST);
if (!empty($_POST)) {

 

  // Eliminar Producto
  if ($_POST['action'] == 'delProduct') {
      if (empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])) {
        echo "error";
      }else {

      $idproducto = $_REQUEST['producto_id'];
      $query_delete = mysqli_query($conexion, "UPDATE producto SET estado = 0 WHERE codproducto = $idproducto");
      mysqli_close($conexion);

     }
    echo "error";
    exit;
  }

  // Buscar Cliente   ***  HECHO 
  if ($_POST['action'] == 'searchCliente') {
      if (($_POST['cliente'] == 0 ) 
        || ($_POST['cliente'] > 0 ) ) {
        $dni = $_POST['cliente'];

        $query = mysqli_query($conexion, "SELECT * FROM cliente WHERE nit_ci LIKE $dni");
        mysqli_close($conexion);
        $result = mysqli_num_rows($query);
        $data = '';
        if ($result > 0) {
          $data = mysqli_fetch_assoc($query);
        }else {
          $data = 0;
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
      }
      exit;
  }


//************************************************************************************* */
//                                   MEDICAMENTOS                                       */
//************************************************************************************* */
  // Extrae datos de los Medicamentos = Registro de lote  * 1 
  if ($_POST['action'] == 'searchMedRegLote') {
    if (($_POST['smrl'] > 0)) {
      $idsmrl = $_POST['smrl'];
      $query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
                                        pa.medicamento AS despactivo, m.preciounitario,
                                        f.nombre AS desforma,
                                        ate.nombre AS desaterapeutica,
                                        un.abreviatura AS desunidad                                      
                                        FROM medicamento m
                                        INNER JOIN pactivo pa ON m.idpactivo = pa.id
                                        INNER JOIN forma f ON m.idforma = f.idforma
                                        INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
                                        INNER JOIN unidades un ON m.idunidad = un.idunidad                                      
                                        WHERE idmedicamento LIKE $idsmrl");

      mysqli_close($conexion);
      $result = mysqli_num_rows($query);
      $data = '';
      if ($result > 0) {
        $data = mysqli_fetch_assoc($query);
        $data['nombrecompleto'] = $data['nombre']. ' - '. $data['despactivo']. ' '. $data['desforma'].' '. $data['concentracion'].''. $data['desunidad'];
        }else {
        $data = 0;
      }
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }
    exit;
  }

  // AGREGAR MEDICAMENTOS AL DETALLE TEMPORAL = REGISTRO DE LOTE * 2
  if ($_POST['action'] == 'addMedRegLote') {
    if (empty($_POST['fecvencimiento'])){
      echo 'error';
    }else {
      $idmedicamento = $_POST['idmedicamento'];
      $cantidad = $_POST['cantidad'];
      $descripcion = $_POST['descripcion'];
      $fecvencimiento = $_POST['fecvencimiento'];
      $preciounitario = $_POST['preciounitario'];
      $preciocen = $_POST['preciocen'];
      $preciosuc = $_POST['preciosuc'];
      $token = md5($_SESSION['idUser']);

      $query_con = mysqli_query($conexion, "INSERT INTO detalle_temp_lote(token_user, codmedicamento, cantidad, precio_compra, preciocen, preciosuc, fecvencimiento, nombrecompleto) 
                                            VALUES ('$token', $idmedicamento, $cantidad, $preciounitario, $preciocen, $preciosuc, '$fecvencimiento', '$descripcion')");
      $query_save = mysqli_query($conexion, "SELECT token_user, correlativo, codmedicamento, cantidad, precio_compra, preciocen,
                                            preciosuc, fecvencimiento, nombrecompleto FROM detalle_temp_lote 
                                            WHERE token_user = '$token'");
      
     
      // $query_detalle_temp = mysqli_query($conexion, "CALL add_detalle_temp_lote('$token', $idmedicamento, $cantidad, $preciounitario, '$fecvencimiento','$descripcion', $preciocen, $preciosuc)");
      $result = mysqli_num_rows($query_save);

      $detalleTabla = '';
      $sub_total = 0;     
      $total = 0;
      $arrayData = array();
      if ($result > 0) {          
     
        while ($data = mysqli_fetch_assoc($query_save)) {
          $precioTotal = round($data['cantidad'] * $data['precio_compra'], 2);
          $sub_total = round($sub_total + $precioTotal, 2);
          $total = round($total + $precioTotal, 2);

            $detalleTabla .='<tr class="align-middle">
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['codmedicamento'].'</td>               
                <td  style="font-size:12px" width="500px" >'.$data['nombrecompleto'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['cantidad'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['fecvencimiento'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['precio_compra'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['preciocen'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['preciosuc'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center" >'.$precioTotal.'</td>
                <td  style="font-size:14px" width="100px"  class="text-center" colspan="2">
                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); del_product_detalle_lote('.$data['correlativo'].');"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>';
        }
        $detalleTotales ='<tr>          
            <td colspan="7" class="text-right align-middle h5 textright text-rojo">Total Bs/.</td>
            <td class="h5 text-center text-verde">'.$total.'</td>
        </tr>';
        $arrayData['detalle1'] = $detalleTabla;
        $arrayData['totales1'] = $detalleTotales;
        echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
      }else {
        echo 'error';
      }
      mysqli_close($conexion);

      }
    exit;
  }

  
  // ANULAR REGISTRO DE LOTE * 3
  if ($_POST['action'] == 'cancelarRegLote') {
    $data = "";
    $token = md5($_SESSION['idUser']);
    $query_del = mysqli_query($conexion, "DELETE FROM detalle_temp_lote WHERE token_user = '$token'");
    mysqli_close($conexion);
    if ($query_del) {
      echo 'ok';
    }else {
      $data = 0;
    }
    exit;
  }

  // extrae datos del detalle temp  *4 delProductoDetalle
  if ($_POST['action'] == 'delDetalleRegistroLote') {
    if (empty($_POST['id_detalle'])){
      echo 'error';
      // code...
    }else {
      $id_detalle = $_POST['id_detalle'];
      $token = md5($_SESSION['idUser']);     

      $query_detalle_tmp_lote = mysqli_query($conexion, "CALL del_detalle_temp_lote($id_detalle,'$token')");
      $result = mysqli_num_rows($query_detalle_tmp_lote);

      $detalleTabla = '';
      $sub_total = 0;      
      $total = 0;
        $data = "";
      $arrayDatadata = array();
      if ($result > 0) {      
        while ($data = mysqli_fetch_assoc($query_detalle_tmp_lote)) {
          $precioTotal = round($data['cantidad'] * $data['precio_compra'], 2);
          $sub_total = round($sub_total + $precioTotal, 2);
          $total = round($total + $precioTotal, 2);

            $detalleTabla .= '<tr class="align-middle">
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['codmedicamento'].'</td>               
                <td  style="font-size:12px" width="500px" >'.$data['nombrecompleto'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['cantidad'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['fecvencimiento'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['precio_compra'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['preciocen'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center">'.$data['preciosuc'].'</td>
                <td  style="font-size:14px" width="100px" class="text-center" >'.$precioTotal.'</td>
                <td  style="font-size:14px" width="100px"  class="text-center" colspan="2">
                <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); del_product_detalle_lote('.$data['correlativo'].');"><i class="fas fa-trash-alt"></i> </a>
                </td>
            </tr>';
        }      
      $detalleTotales = '<tr>
        <td colspan="7" class="align-middle h5 text-right text-rojo">Total Bs/.</td>
        <td class="h5 text-center text-verde">'.$total.'</td>
        </tr>';

      $arrayData['detalle1'] = $detalleTabla;
      $arrayData['totales1'] = $detalleTotales;

      echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
    }else {
      $data = 0;
    }
    mysqli_close($conexion);

    }
    exit;
  }

     //procesar registro de lote 5 
  if ($_POST['action'] == 'procesarRegLote') {
    if (empty($_POST['proveedorlote'])) {
      
    }else{
      $proveedorlote = $_POST['proveedorlote'];
      $descripcionlote = $_POST['descripcionlote'];
      $estadolote = $_POST['estadolote'];
      $token = md5($_SESSION['idUser']);
      $usuario = $_SESSION['idUser'];
      $query = mysqli_query($conexion, "SELECT * FROM detalle_temp_lote WHERE token_user = '$token' ");
      $result = mysqli_num_rows($query);
    }

    if ($result > 0) {      
      $query_procesar_reglote = mysqli_query($conexion, "CALL procesar_reglote($usuario,$proveedorlote,'$token', '$descripcionlote', $estadolote)");
      $result_detalle = mysqli_num_rows($query_procesar_reglote);
      if ($result_detalle > 0) {
        $data = mysqli_fetch_assoc($query_procesar_reglote);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
      }else {
        echo "error-1";
      }
    }else {
      echo "error";
    }
    mysqli_close($conexion);
    exit;
  }
  



  //************************************************************************************* */
//                                 VENTAS MEDICAMENTOS                                  */
//************************************************************************************* */

  
  // Extraer datos del producto  *1
  if ($_POST['action'] == 'infoProducto') {
    $data = "";
    $producto_id = $_POST['producto'];
    $query = mysqli_query($conexion, "SELECT * 
                                    FROM producto 
                                    WHERE codproducto = $producto_id");
    $result = mysqli_num_rows($query);
    if ($result > 0) {
      $data = mysqli_fetch_assoc($query);
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
      exit;
    }else {
      $data = 0;
    }
  }



 


   




//************************************************************************************* */
//                                     CLIENTES                                         */
//************************************************************************************* */

// registrar cliente = MODULO DE VENTAS
  if ($_POST['action'] == 'addCliente') {
    $dni = $_POST['dni_cliente'];
    $nomnre = $_POST['nom_cliente'];
    $telefono = $_POST['tel_cliente'];
    $direccion = $_POST['dir_cliente'];
    $usuario_id = $_SESSION['idUser'];

    $query_insert = mysqli_query($conexion, "INSERT INTO cliente(nit_ci, nombre, telefono, 
                                            direccion, usuario_id) 
                                            VALUES ('$dni','$nomnre','$telefono',
                                            '$direccion','$usuario_id')");
    if ($query_insert) {
      $codCliente = mysqli_insert_id($conexion);
      $msg = $codCliente;
    }else {
      $msg = 'error';
    }
    mysqli_close($conexion);
    echo $msg;
    exit;
  }

  // agregar producto a detalle temporal  * 2
  if ($_POST['action'] == 'addProductoDetalle') {
    if (empty($_POST['producto']) || empty($_POST['cantidad'])){
      echo 'error';
    }else {
      $codproducto = $_POST['producto'];
      $cantidad = $_POST['cantidad'];
      $token = md5($_SESSION['idUser']);
      $query_iva = mysqli_query($conexion, "SELECT iva FROM configuracion");
      $result_iva = mysqli_num_rows($query_iva);
      $query_detalle_temp = mysqli_query($conexion, "CALL add_detalle_temp ($codproducto,$cantidad,'$token')");
      $result = mysqli_num_rows($query_detalle_temp);

      $detalleTabla = '';
      $sub_total = 0;
      $iva = 0;
      $total = 0;
      $arrayData = array();
      if ($result > 0) {
          
      if ($result_iva > 0) {
        $info_iva = mysqli_fetch_assoc($query_iva);
        $iva = $info_iva['iva'];
      }
      while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
        $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
        $sub_total = round($sub_total + $precioTotal, 2);
        $total = round($total + $precioTotal, 2);

          $detalleTabla .='<tr>
              <td>'.$data['codproducto'].'</td>
              <td colspan="2">'.$data['descripcion'].'</td>
              <td class="textcenter">'.$data['cantidad'].'</td>
              <td class="textright">'.$data['precio_venta'].'</td>
              <td class="textright">'.$precioTotal.'</td>
              <td>
                  <a href="#" class="btn btn-danger" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fas fa-trash-alt"></i> Eliminar</a>
              </td>
          </tr>';
      }
      $impuesto = round($sub_total * $iva, 2);
      $tl_sniva = round($sub_total - $impuesto, 2);
      $total = round($tl_sniva + $impuesto, 2);
      $detalleTotales ='<tr>
          <td colspan="5" class="textright">Sub_Total S/.</td>
          <td class="textright">'.$tl_sniva.'</td>
      </tr>
      <tr>
          <td colspan="5" class="textright">IVA ('.$iva.'%)</td>
          <td class="textright">'. $impuesto.'</td>
      </tr>
      <tr>
          <td colspan="5" class="textright">Total S/.</td>
          <td class="textright">'.$total.'</td>
      </tr>';
      
      $arrayData['detalle'] = $detalleTabla;
      $arrayData['totales'] = $detalleTotales;
      echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
    }else {
      echo 'error';
    }
    mysqli_close($conexion);

    }
    exit;
  }

  // Anular Ventas * 3
  if ($_POST['action'] == 'anularVenta') {
    $data = "";
    $token = md5($_SESSION['idUser']);
    $query_del = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE token_user = '$token'");
    mysqli_close($conexion);
    if ($query_del) {
      echo 'ok';
    }else {
      $data = 0;
    }
    exit;
  }


  


  // extrae datos del detalle tem   --   NOOOO
  if ($_POST['action'] == 'searchForDetalle') {

    if (empty($_POST['user'])){
      echo 'error';
    }else {
      $token = md5($_SESSION['idUser']);

      $query = mysqli_query($conexion, "SELECT tmp.correlativo, tmp.token_user,
        tmp.cantidad, tmp.precio_venta, p.codproducto, p.descripcion
        FROM detalle_temp tmp INNER JOIN producto p ON tmp.codproducto = p.codproducto
        where token_user = '$token'");
      $result = mysqli_num_rows($query);

      $query_iva = mysqli_query($conexion, "SELECT iva FROM configuracion");
      $result_iva = mysqli_num_rows($query_iva);


      $detalleTabla = '';
      $sub_total = 0;
      $iva = 0;
      $total = 0;
      $data = "";
      $arrayDatadata = array();
      if ($result > 0) {
      if ($result_iva > 0) {
        $info_iva = mysqli_fetch_assoc($query_iva);
        $iva = $info_iva['iva'];
      }
      while ($data = mysqli_fetch_assoc($query)) {
        $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
        $sub_total = round($sub_total + $precioTotal, 2);
        $total = round($total + $precioTotal, 2);

          $detalleTabla .= '<tr>
              <td>'.$data['codproducto'].'</td>
              <td colspan="2">'.$data['descripcion'].'</td>
              <td class="textcenter">'.$data['cantidad'].'</td>
              <td class="textright">'.$data['precio_venta'].'</td>
              <td class="textright">'.$precioTotal.'</td>
              <td>
                  <a href="#" class="link_delete" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fas fa-trash-alt"></i> Eliminar</a>
              </td>
          </tr>';
      }
      $impuesto = round($sub_total / $iva, 2);
      $tl_sniva = round($sub_total - $impuesto, 2);
      $total = round($tl_sniva + $impuesto, 2);

      $detalleTotales = '<tr>
          <td colspan="5" class="textright">Sub_Total S/.</td>
          <td class="textright">'.$impuesto.'</td>
      </tr>
      <tr>
          <td colspan="5" class="textright">IVA ('.$iva.')</td>
          <td class="textright">'. $tl_sniva.'</td>
      </tr>
      <tr>
          <td colspan="5" class="textright">Total S/.</td>
          <td class="textright">'.$total.'</td>
      </tr>';

      $arrayData['detalle'] = $detalleTabla;
      $arrayData['totales'] = $detalleTotales;

      echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
      exit;
    }else {
      $data = 0;
      exit;
    }
    mysqli_close($conexion);

    }
    exit;
  }

  // extrae datos del detalle temp *4
  if ($_POST['action'] == 'delProductoDetalle') {
    if (empty($_POST['id_detalle'])){
      echo 'error';
      // code...
    }else {
      $id_detalle = $_POST['id_detalle'];
      $token = md5($_SESSION['idUser']);


      $query_iva = mysqli_query($conexion, "SELECT iva FROM configuracion");
      $result_iva = mysqli_num_rows($query_iva);

      $query_detalle_tmp = mysqli_query($conexion, "CALL del_detalle_temp($id_detalle,'$token')");
      $result = mysqli_num_rows($query_detalle_tmp);

      $detalleTabla = '';
      $sub_total = 0;
      $iva = 0;
      $total = 0;
        $data = "";
      $arrayDatadata = array();
      if ($result > 0) {
      if ($result_iva > 0) {
        $info_iva = mysqli_fetch_assoc($query_iva);
        $iva = $info_iva['iva'];
      }
      while ($data = mysqli_fetch_assoc($query_detalle_tmp)) {
        $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
        $sub_total = round($sub_total + $precioTotal, 2);
        $total = round($total + $precioTotal, 2);

          $detalleTabla .= '<tr>
              <td>'.$data['codproducto'].'</td>
              <td colspan="2">'.$data['descripcion'].'</td>
              <td class="textcenter">'.$data['cantidad'].'</td>
              <td class="textright">'.$data['precio_venta'].'</td>
              <td class="textright">'.$precioTotal.'</td>
              <td>
                  <a href="#" class="link_delete" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');">Eliminar</a>
              </td>
          </tr>';
      }
      $impuesto = round($sub_total / $iva, 2);
      $tl_sniva = round($sub_total - $impuesto, 2);
      $total = round($tl_sniva + $impuesto, 2);

      $detalleTotales = '<tr>
          <td colspan="5" class="textright">Sub_Total S/.</td>
          <td class="textright">'.$impuesto.'</td>
      </tr>
      <tr>
          <td colspan="5" class="textright">IVA ('.$iva.')</td>
          <td class="textright">'. $tl_sniva.'</td>
      </tr>
      <tr>
          <td colspan="5" class="textright">Total S/.</td>
          <td class="textright">'.$total.'</td>
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

 

  //procesarVenta 5 
  if ($_POST['action'] == 'procesarVenta') {
    if (empty($_POST['codcliente'])) {
      $codcliente = 1;
    }else{
      $codcliente = $_POST['codcliente'];

      $token = md5($_SESSION['idUser']);
      $usuario = $_SESSION['idUser'];
      $query = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE token_user = '$token' ");
      $result = mysqli_num_rows($query);
    }

    if ($result > 0) {
      $query_procesar = mysqli_query($conexion, "CALL procesar_venta($usuario,$codcliente,'$token')");
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



  //procesarGuia
  if ($_POST['action'] == 'procesarGuia') {
    if (empty($_POST['codcliente'])) {
      $codcliente = 1;
    } else {
      $codcliente = $_POST['codcliente'];

      $token = md5($_SESSION['idUser']);
      $usuario = $_SESSION['idUser'];
      $query = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE token_user = '$token' ");
      $result = mysqli_num_rows($query);
    }

    if ($result > 0) {
      $query_procesar = mysqli_query($conexion, "CALL procesar_guia($usuario,$codcliente,'$token')");
      $result_detalle = mysqli_num_rows($query_procesar);
      if ($result_detalle > 0) {
        $data = mysqli_fetch_assoc($query_procesar);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
      } else {
        echo "error";
      }
    } else {
      echo "error";
    }
    mysqli_close($conexion);
    exit;
  }

  //procesarBoleta
  if ($_POST['action'] == 'procesarBoleta') {
    if (empty($_POST['codcliente'])) {
      $codcliente = 1;
    } else {
      $codcliente = $_POST['codcliente'];

      $token = md5($_SESSION['idUser']);
      $usuario = $_SESSION['idUser'];
      $query = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE token_user = '$token' ");
      $result = mysqli_num_rows($query);
    }

    if ($result > 0) {
      $query_procesar = mysqli_query($conexion, "CALL procesar_boleta($usuario,$codcliente,'$token')");
      $result_detalle = mysqli_num_rows($query_procesar);
      if ($result_detalle > 0) {
        $data = mysqli_fetch_assoc($query_procesar);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
      } else {
        echo "error";
      }
    } else {
      echo "error";
    }
    mysqli_close($conexion);
    exit;
  }

  // Info factura
  if ($_POST['action'] == 'infoFactura') {
    if (!empty($_POST['nofactura'])) {
      $nofactura = $_POST['nofactura'];
      $query = mysqli_query($conexion, "SELECT * FROM factura WHERE nofactura = '$nofactura' AND estado = 1");
      mysqli_close($conexion);
      $result = mysqli_num_rows($query);
      if ($result > 0) {
        $data = mysqli_fetch_assoc($query);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
      }
    }
    echo "error";
    exit;
  }

  // anular factura
  if ($_POST['action'] == 'anularFactura') {
    if (!empty($_POST['noFactura'])) {
        $data = "";
      $noFactura = $_POST['noFactura'];
      $query_anular = mysqli_query($conexion, "CALL anular_factura($noFactura)");
      mysqli_close($conexion);
      $result = mysqli_num_rows($query_anular);
      if ($result > 0) {
        $data = mysqli_fetch_assoc($query_anular);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
      }
    }
    $data = 0;
    exit;
  }

  // Cambiar contraseña
  if ($_POST['action'] == 'changePasword') {
      if (!empty($_POST['passActual']) && !empty($_POST['passNuevo'])) {
        $password = md5($_POST['passActual']);
        $newPass = md5($_POST['passNuevo']);
        $idUser = $_SESSION['idUser'];
        $code = '';
        $msg = '';
        $arrayData = array();
        $query_user = mysqli_query($conexion, "SELECT * FROM usuario WHERE clave = '$password' AND idusuario = $idUser");
        $result = mysqli_num_rows($query_user);
        if ($result > 0) {
          $query_update = mysqli_query($conexion, "UPDATE usuario SET clave = '$newPass' where idusuario = $idUser");
          mysqli_close($conexion);
          if ($query_update) {
            $code = '00';
            $msg = "su contraseña se ha actualizado con exito";
            header("Refresh:1; URL=salir.php");
          }else {
            $code = '2';
            $msg = "No es posible actualizar su contraseña";
          }
        }else {
          $code = '1';
          $msg = "La contraseña actual es incorrecta";
        }
        $arrayData = array('cod' => $code, 'msg' => $msg);
        echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
      }else {
        echo "error";
      }
    exit;
  }


exit;
}
?>
