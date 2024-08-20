<?php
include("../../conexion.php");
session_start();
print_r($_POST);
if (!empty($_POST)) {
  
// PRODECIMIENTOS KARDEX 

  // CASO 1 : REGISTRO DE LOTE  ingreso de proveedor
  if ($_POST['action'] == 'kardex_reg_lote') {
    if (empty($_POST['notaing'])) {   
      //code... 
    }else{
      $notaing = $_POST['notaing'];
      $token = md5($_SESSION['idUser']);
      $usuario = $_SESSION['idUser'];
      $query = mysqli_query($conexion, "SELECT l.nolote AS idlote, l.fecha, l.codproveedor, 
                                        dl.codmedicamento, dl.fecvencimiento, dl.cantidad, dl.precio_compra,
                                        m.preciounitario
                                        FROM  lote l 
                                        INNER JOIN detallelote  dl ON l.nolote = dl.nolote 
                                        INNER JOIN medicamento m ON dl.codmedicamento = m.idmedicamento 
                                        WHERE l.nolote = '$notaing'");
      $result = mysqli_num_rows($query);
    }
    if ($result > 0) { 
      while ($datoslote = mysqli_fetch_array($query)) {
        $nolote = $datoslote['idlote'];
        $fechareg = $datoslote['fecha'];
        $codproveedor = $datoslote['codproveedor'];
        $codmedicamento = $datoslote['codmedicamento'];
        $fecvencimiento = $datoslote['fecvencimiento'];
        $cantidad = $datoslote['cantidad'];
        $preciocompra = $datoslote['precio_compra'];
        $precioventa = $datoslote['preciounitario'];     
      
        //registramos los datos       
        $query_insert = mysqli_query($conexion, "INSERT INTO kardex(fechamovimiento, idtipomov, nodoc, idproveedor,
                                                  idmedicamento, fechavencimiento, idsucorigen, idsucdestino, preciocompra,
                                                  precioventa, cantidad, idusuario) 
                                                  VALUES ('$fechareg', 1 , $nolote, $codproveedor, $codmedicamento,
                                                  '$fecvencimiento', NULL, 1, $preciocompra, $precioventa, $cantidad, $usuario)");

        // VERIFICAMOS SI EXISTE REGISTRO EN LA TABLA STOCK
        $query_stock = mysqli_query($conexion, "SELECT correlativo, saldo FROM stock WHERE idmedicamento = $codmedicamento AND fechavencimiento = '$fecvencimiento'");
        $result_stock = mysqli_num_rows($query_stock);
          if ($result_stock > 0) {
            // ACTUALIZAMOS EL MEDICAMENTO EN LA TABLA STOCK
            $datos_stock = mysqli_fetch_array($query_stock);
            $cor_s = $datos_stock['correlativo'];
            $saldo_actual_s = $datos_stock['saldo'];
            $nuevo_saldo_s = $saldo_actual_s + $cantidad;        
            $query_update_stock = mysqli_query($conexion, "UPDATE stock SET saldo = $nuevo_saldo_s
                                                WHERE correlativo = $cor_s");
          
          } else {
            // REGISTRAMOS EL MEDICAMENTO EN LA TABLA STOCK
            $query_insert_stock = mysqli_query($conexion, "INSERT INTO stock(idsuc, idmedicamento, fechavencimiento, saldo)
            VALUES (1, $codmedicamento, '$fecvencimiento', $cantidad)");
          } 
      }    
      mysqli_close($conexion);
      exit;
    }
    exit;
  }

// PROCEDIMIENTOS TRASPASOS

  //CASO 1 : TRASPASO DE ALMACEN A SUCURSAL kardex_reg_traspaso kardex_reg_devolucion_suc
  if ($_POST['action'] == 'kardex_reg_traspaso') {
    if (empty($_POST['notatraspaso'])) {   
      //code... 
    }else{
      $notatraspaso = $_POST['notatraspaso'];
      $token = md5($_SESSION['idUser']);
      $usuario = $_SESSION['idUser'];
      $query = mysqli_query($conexion, "SELECT t.notraspaso AS idtraspaso, t.idsucorigen, t.idsucdestino, t.fecha,
                                      dt.fechavencimiento, dt.idmedicamento, dt.precioventa, dt.preciocompra, dt.cantidad
                                      FROM  traspaso t 
                                      INNER JOIN detalletraspaso dt ON t.notraspaso = dt.notraspaso 
                                      WHERE t.notraspaso = '$notatraspaso'");
      $result = mysqli_num_rows($query);
    }
    if ($result > 0) { 
      while ($datostraspaso = mysqli_fetch_array($query)) {
        $notatraspaso = $datostraspaso['idtraspaso'];
        $fechatr = $datostraspaso['fecha'];
        $idsucorigen = $datostraspaso['idsucorigen'];
        $idsucdestino = $datostraspaso['idsucdestino'];
        $codmedicamento = $datostraspaso['idmedicamento'];
        $fecvencimiento = $datostraspaso['fechavencimiento'];
        $cantidad = $datostraspaso['cantidad'];
        $precioventa = $datostraspaso['precioventa'];
        $preciocompra = $datostraspaso['preciocompra'];
        
      
        //registramos los datos       
        $query_insert = mysqli_query($conexion, "INSERT INTO kardex(fechamovimiento, idtipomov, nodoc, idproveedor,
                                                  idmedicamento, fechavencimiento, idsucorigen, idsucdestino, preciocompra,
                                                  precioventa, cantidad, idusuario ) 
                                                  VALUES ('$fechatr', 4 , $notatraspaso, NULL, $codmedicamento,
                                                  '$fecvencimiento', 1, $idsucdestino, $preciocompra, $precioventa, $cantidad, $usuario)");      
          
          // VERIFICAMOS SI EXISTE REGISTRO EN LA TABLA STOCK
          $query_stock = mysqli_query($conexion, "SELECT correlativo, saldo FROM stock WHERE idmedicamento = $codmedicamento AND fechavencimiento = '$fecvencimiento'");
          $result_stock = mysqli_num_rows($query_stock);
            if ($result_stock > 0) {
              // ACTUALIZAMOS EL MEDICAMENTO EN LA TABLA STOCK
              $datos_stock = mysqli_fetch_array($query_stock);
              $cor_s = $datos_stock['correlativo'];
              $saldo_actual_s = $datos_stock['saldo'];
              $nuevo_saldo_s = $saldo_actual_s - $cantidad;        
              $query_update_stock = mysqli_query($conexion, "UPDATE stock SET saldo = $nuevo_saldo_s
                                                  WHERE correlativo = $cor_s");

              // VERIFICAMOS SI EXISTE EL MEDICAMENTO EN LA SUCURSAL DE DESTINO
              $query_stock_v = mysqli_query($conexion, "SELECT correlativo, saldo FROM stock WHERE idmedicamento = $codmedicamento AND fechavencimiento = '$fecvencimiento' AND idsuc = $idsucdestino");
              $result_stock_v = mysqli_num_rows($query_stock_v);
              if ($result_stock_v > 0) {
                // ACTUALIZAMOS EL MEDICAMENTO EN LA TABLA STOCK
                $datos_stock_v = mysqli_fetch_array($query_stock_v);
                $cor_v = $datos_stock_v['correlativo'];
                $saldo_actual_v = $datos_stock_v['saldo'];
                $nuevo_saldo_v = $saldo_actual_v + $cantidad;        
                $query_update_stock_v = mysqli_query($conexion, "UPDATE stock SET saldo = $nuevo_saldo_v
                                                    WHERE correlativo = $cor_v");
              } else {
                // REGISTRAMOS EL MEDICAMENTO EN LA TABLA STOCK
                $query_insert_stock_v = mysqli_query($conexion, "INSERT INTO stock(idsuc, idmedicamento, fechavencimiento, saldo)
                VALUES ($idsucdestino, $codmedicamento, '$fecvencimiento', $cantidad)");           
              } 
            } else {
              // SIEMPRE EXISTIRA UN REGISTRO PARA EL TRASPASO...              
            } 
        }    
        mysqli_close($conexion);
      exit;
    }
    exit;
  }

   //CASO 2 : DEVOLUCION DE SUCURSAL A ALMACEN  kardex_reg_devolucion_suc
  if ($_POST['action'] == 'kardex_reg_devolucion_suc') {
    if (empty($_POST['notadevolucion'])) {   
      //code... 
    }else{
      $notadevolucion = $_POST['notadevolucion'];
      $token = md5($_SESSION['idUser']);
      $usuario = $_SESSION['idUser'];
      $query = mysqli_query($conexion, "SELECT ds.nodevsuc AS iddev, ds.fecha, ds.idsucdestino, ds.idsucorigen,
                                      dt.fechavencimiento, dt.idmedicamento, dt.precioventa, dt.preciocompra, dt.cantidad
                                      FROM  devolucion_suc ds 
                                      INNER JOIN detalledevolucionsuc dt ON ds.nodevsuc = dt.nodevsuc
                                      WHERE ds.nodevsuc = $notadevolucion");
      $result = mysqli_num_rows($query);
    }
    if ($result > 0) { 
      while ($datosdevolucion = mysqli_fetch_array($query)) {
        $notadevolucion = $datosdevolucion['iddev'];
        $fechatr = $datosdevolucion['fecha'];
        $idsucorigen = $datosdevolucion['idsucorigen'];
        $idsucdestino = $datosdevolucion['idsucdestino'];
        $codmedicamento = $datosdevolucion['idmedicamento'];
        $fecvencimiento = $datosdevolucion['fechavencimiento'];
        $cantidad = $datosdevolucion['cantidad'];
        $precioventa = $datosdevolucion['precioventa'];
        $preciocompra = $datosdevolucion['preciocompra'];
        
      
        //registramos los datos       
        $query_insert = mysqli_query($conexion, "INSERT INTO kardex(fechamovimiento, idtipomov, nodoc, idproveedor,
                                                  idmedicamento, fechavencimiento, idsucorigen, idsucdestino, preciocompra,
                                                  precioventa, cantidad, idusuario ) 
                                                  VALUES ('$fechatr', 6 , $notadevolucion, NULL, $codmedicamento,
                                                  '$fecvencimiento', $idsucorigen, $idsucdestino, $preciocompra, $precioventa, $cantidad, $usuario)");      
          
          // VERIFICAMOS SI EXISTE REGISTRO EN LA TABLA STOCK
          $query_stock = mysqli_query($conexion, "SELECT correlativo, saldo FROM stock WHERE idmedicamento = $codmedicamento AND fechavencimiento = '$fecvencimiento' AND idsuc = 1");
          $result_stock = mysqli_num_rows($query_stock);
            if ($result_stock > 0) {
              // ACTUALIZAMOS EL MEDICAMENTO EN LA TABLA STOCK
              $datos_stock = mysqli_fetch_array($query_stock);
              $cor_s = $datos_stock['correlativo'];
              $saldo_actual_s = $datos_stock['saldo'];
              $nuevo_saldo_s = $saldo_actual_s + $cantidad;        
              $query_update_stock = mysqli_query($conexion, "UPDATE stock SET saldo = $nuevo_saldo_s
                                                  WHERE correlativo = $cor_s");

              // RESTAMOS EL STOCK DEL MEDICAMENTE EN LA SUCURSAL DE ORIGEN DE LA DEVOLUCION
              $query_stock_v = mysqli_query($conexion, "SELECT correlativo, saldo FROM stock WHERE idmedicamento = $codmedicamento AND fechavencimiento = '$fecvencimiento' AND idsuc = $idsucorigen ");
              $result_stock_v = mysqli_num_rows($query_stock_v);
              if ($result_stock_v > 0) {
                // ACTUALIZAMOS EL MEDICAMENTO EN LA TABLA STOCK
                $datos_stock_v = mysqli_fetch_array($query_stock_v);
                $cor_v = $datos_stock_v['correlativo'];
                $saldo_actual_v = $datos_stock_v['saldo'];
                $nuevo_saldo_v = $saldo_actual_v - $cantidad;        
                $query_update_stock_v = mysqli_query($conexion, "UPDATE stock SET saldo = $nuevo_saldo_v
                                                    WHERE correlativo = $cor_v");
              } else {
                     // SIEMPRE EXISTIRA UN REGISTRO PARA DEVOLUCION...     
              } 
            } else {
              // SIEMPRE EXISTIRA UN REGISTRO PARA EL TRASPASO...              
            } 
        }    
        mysqli_close($conexion);
      exit;
    }
    exit;
  }

  // CASO 3: VENTA  kardex_venta
  if ($_POST['action'] == 'kardex_venta') {
    if (empty($_POST['nofactura'])) {   
      //code... 
    }else{
      $nofactura = $_POST['nofactura'];
      $token = md5($_SESSION['idUser']);
      $usuario = $_SESSION['idUser'];

      $query = mysqli_query($conexion, "SELECT f.nofactura, f.fecha, f.totalfactura, 
                                      df.codmedicamento, df.fechavencimiento, df.cantidad, df.precio_venta
                                      FROM facturas f
                                      INNER JOIN detallefacturas df ON f.nofactura = df.nofactura
                                      WHERE f.nofactura = $nofactura");                                   

      $result = mysqli_num_rows($query);
    }
    if ($result > 0) { 
      
     
      while ($datosventa = mysqli_fetch_array($query)) {
        $nofactura = $datosventa['nofactura'];
        $fechav = $datosventa['fecha'];      
        $codmedicamento = $datosventa['codmedicamento'];
        $fecvencimiento = $datosventa['fechavencimiento'];
        $cantidad = $datosventa['cantidad'];
        $precioventa = $datosventa['precio_venta'];       
        $totalfactura = $datosventa['totalfactura'];

        $idsucorigen = $_SESSION['idsuc'];          
        $fecha_actual = date("Y-m-d");  

        //registramos los datos cajas
        $query_ccajas = mysqli_query($conexion, "SELECT id, ventas FROM cajas WHERE fecha = '$fechav' AND idusuario = $usuario");
        $resultado_ccajas = mysqli_num_rows($query_ccajas);
        if ($resultado_ccajas > 0) {
          $dataventas = mysqli_fetch_assoc($query_ccajas);
          $reg_venta = $dataventas['ventas'];
          $correlativo = $dataventas['id'];        
          $venta_actu = $reg_venta + ($precioventa * $cantidad);
          $venta_actu = number_format($venta_actu, 2, '.', '');

          $query_update = mysqli_query($conexion, "UPDATE cajas SET ventas = $venta_actu WHERE id = $correlativo");
        } 
        
       

        $query_insert = mysqli_query($conexion, "INSERT INTO kardex(fechamovimiento, idtipomov, nodoc, idproveedor,
                                                  idmedicamento, fechavencimiento, idsucorigen, idsucdestino, preciocompra,
                                                  precioventa, cantidad, idusuario ) 
                                                  VALUES ('$fechav', 7 , $nofactura, NULL, $codmedicamento,
                                                  '$fecvencimiento', $idsucorigen, NULL, NULL, $precioventa, $cantidad, $usuario)");      
          
          // VERIFICAMOS SI EXISTE REGISTRO EN LA TABLA STOCK
          $query_stock = mysqli_query($conexion, "SELECT correlativo, saldo FROM stock WHERE idmedicamento = $codmedicamento AND fechavencimiento = '$fecvencimiento' AND idsuc = $idsucorigen");
          $result_stock = mysqli_num_rows($query_stock);
            if ($result_stock > 0) {
              // ACTUALIZAMOS EL MEDICAMENTO EN LA TABLA STOCK
              $datos_stock = mysqli_fetch_array($query_stock);
              $cor_s = $datos_stock['correlativo'];
              $saldo_actual_s = $datos_stock['saldo'];
              $nuevo_saldo_s = $saldo_actual_s - $cantidad;        
              $query_update_stock = mysqli_query($conexion, "UPDATE stock SET saldo = $nuevo_saldo_s
                                                  WHERE correlativo = $cor_s");
              
            } else {
              // SIEMPRE EXISTIRA UN REGISTRO PARA LA VENTA...              
            } 
        }    
        mysqli_close($conexion);
      exit;
    }
    exit;
  }
  

}?>
