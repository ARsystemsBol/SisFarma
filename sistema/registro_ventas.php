<?php include_once "includes/header.php";
  include "../conexion.php";
    
?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">
    <!-- Page breadcrumb -->	
<!--     <nav aria-label=" breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>
        <li class="breadcrumb-item"><a href="registro_ventas.php"><i class="fas fa-money-bill text-info"></i></a></li>
        <li class="breadcrumb-item active" aria-current="page">Nueva Venta</li>
    </ol>
    </nav> -->
    <!-- End Page breadcrumb -->

    <!-- Content Row -->
    <div class="card">
        <div class="card-header bg-azul text-white ">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h6 mb-0 text-uppercase">Nueva Venta</h1>
                <a href="index.php" class="btn btn-danger btn-sm">Regresar</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <h4 class="h6 text-center text-gray-600">DATOS CLIENTE</h4>
                        <a href="#" class="btn btn-primary btn-sm btn_new_cliente mr-2"><i
                                class="fas fa-user-plus mr-1"></i> Nuevo Cliente</a>
                        <button id="btn_cnc" class="btn btn-danger btn-sm btn_cancel_new_cliente"
                            style="display: none;"><i class="fas fa-trash mr-1"></i>Cancelar</button>
                    </div>
                    <div class="card mb-2">
                        <!--SECCION DATOS DEL CLIENTE -->
                        <div class="card-body">
                            <form method="post" name="form_new_cliente_venta" id="form_new_cliente_venta">
                                <input type="hidden" name="action" value="addCliente">
                                <input type="hidden" id="idcliente" value="1" name="idcliente" required>
                                <div class="row">
                                    <!-- NIT -->
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input autofocus type="number" placeholder="NIT - CI" name="dni_cliente"
                                                id="dni_cliente" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <!-- NOMBRE -->
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" placeholder="nombre..." name="nom_cliente"
                                                id="nom_cliente" class="form-control form-control-sm" disabled required>
                                        </div>
                                    </div>
                                    <!-- TELEFONO -->
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="number" placeholder="teléfono..." name="tel_cliente"
                                                id="tel_cliente" class="form-control form-control-sm" disabled required>
                                        </div>
                                    </div>
                                    <!-- DIRECCION -->
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" placeholder="dirección..." name="dir_cliente"
                                                id="dir_cliente" class="form-control form-control-sm" disabled required>
                                        </div>
                                    </div>
                                    <!-- BOTONES -->
                                    <div class="row container-fluid col-12">
                                        <div class="mr-4" id="div_registro_cliente" style="display: none;">
                                            <button type="submit" class="btn btn-success btn-sm"><i
                                                    class="fas fa-save mr-1"></i>Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!--SECCION VENDEDOR Y BOTONES DE REGISTRO DE VENTA -->
                    <div class="row">
                        <!-- <div class="col-lg-6">
                            <div class="form-group">
                                <label style="font-size: 14px;"><i class="fas fa-user fa-sm text-info mr-2"></i>
                                    VENDEDOR</label>
                                <p style="font-size: 12px; text-transform: uppercase;" class="text-info">
                                    <?php echo $_SESSION['nombre']; ?>
                                </p>
                            </div>
                        </div> -->
                        <!-- BOTONES DE REGISTRAR LA VENTA Y BUSCAR MEDICAMENTO -->
                        <div class="col-lg-12">
                            <div id="acciones_venta" class="form-group">
                                
                                <a href="#" class="btn btn-success btn-sm pr-3 pl-3" name="btn_buscar_medicamento" id="btn_buscar_medicamento"
                                    data-bs-toggle="modal" data-bs-target="#modventamedicamento"><i
                                        class="fas fa-search mr-2"></i> Buscar medicamento...</a>
                                <a href="#" style="display: none;" class="btn btn-danger btn-sm pr-4 pl-4" name="btn_anular_ventan4" id="btn_anular_ventan4"><i
                                        class="fas fa-trash mr-2"></i> Anular</a>
                                <a href="#" style="display: none;" class="btn btn-primary btn-sm" name="btn_facturar_ventan4" id="btn_facturar_ventan4"><i
                                        class="fas fa-save"></i> Generar Venta</a>
                            </div>
                        </div>
                    </div>
                    <!--SECCION DETALLE DE LA VENTA -->
                    <h4 class="h6 text-center text-gray-600 mb-2">DETALLE DE VENTA</h4>
                    <div class="table-responsive">
                          <table class="table table-sm table-striped table-hover">
                            <thead class="thead-dark">
                                <tr class="text-center" style="font-size:12px">
                                    <th width="30px">Id.</th>
                                    <th width="50px">Descripción</th>
                                    <th width="50px">Stock</th>
                                    <th width="100px">F. Vencimiento</th>
                                    <th width="50px">Cantidad.</th> 
                                    <th width="50px">Precio.</th>                                    
                                    <th width="50px">Precio Total</th>
                                    <th>Sel.</th>
                                </tr>
                                <tr>                                   
                                    <td width="30px" style="font-size:14px" class="text-center align-middle"  name="txt_cod_producton4" id="txt_cod_producton4"></td>
                                    <td style="font-size:12px" class="align-middle" id="txt_descripcionn4" name="txt_descripcionn4">-</td>
                                    <td style="font-size:14px" class="text-center align-middle"  id="txt_existencian4" name="txt_existencian4">-</td>
                                    <td><input class="form-control form-control-sm" type="date" name="txt_fechavencimienton4" id="txt_fechavencimienton4" min="1" disabled></td>
                                    <td><input class="form-control form-control-sm" type="number" name="txt_cant_producton4" id="txt_cant_producton4" min="1" disabled></td>
                                    <td><input class="form-control form-control-sm" type="number" name="txt_precion4" id="txt_precion4" min="1" disabled></td> 
                                    <td style="font-size:14px" class="text-center align-middle" id="txt_precio_totaln4" name="txt_precio_totaln4" class="txtright">0.00</td>
                                    <td class="text-center"><a href="#"  id="add_product_ventan4" name="add_product_ventan4" class="btn btn-outline-success btn-sm"
                                            style="display: none;"><i class="fas fa-arrow-right mr-1"></i></a>
                                    </td>                             
                                </tr>
                                <tr class="text-center align-middle" style="font-size:12px">
                                    <th width="100px">Id.</th>
                                    <th width="400px">Descripción</th>
                                    <th width="100px">Cantidad</th>
                                    <th width="100px">F. Ven.</th>
                                    <th width="100px">Precio</th>
                                    <th width="100px">Sub Total</th>
                                    <th colspan="2" width="100px">Descartar</th>
                                </tr>
                            </thead>
                            <tbody id="detalle_ventan4">
                                <!-- Contenido ajax -->

                            </tbody>

                            <tfoot id="detalle_totalesn4">
                                <!-- Contenido ajax -->
                            </tfoot>
                        </table>

                    </div>
                 
                </div>
            </div>
        </div>

    <!-- ---------------------------------------------------------------------------------------------------------- -->
    <!-- Modal BUSCAR EMEDICAMENTO VENTAS  CODIGO =7 -->
    <div class="modal fade" id="modventamedicamento" tabindex="-1" aria-labelledby="modventamedicamento" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-azul">
                    <h5 class="modal-title text-center h6" id="modventamedicamento">LISTA MEDICAMENTOS - VENTA</h5>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm " id="table">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th style="font-size:10px" width="30px">ID</th>                                  
                                    <th style="font-size:10px" width="400px">DESCRIPCION</th>
                                    <th style="font-size:10px" width="100px">F. VENCIMIENTO</th>
                                    <th style="font-size:10px" width="50px">p/Unit.</th>
                                    <th style="font-size:10px" width="50px">STOCK</th>
                                    <th style="font-size:10px" width="30px"> Sel.</th>
                            </thead>
                            <tbody>
                                <?php
					include "../conexion.php";
                    $suc = $_SESSION['idsuc'];
                
					$query = mysqli_query($conexion, "SELECT s.idsuc AS idss, s.idmedicamento AS idms,
                                                    s.fechavencimiento AS fvs, s.saldo AS saldos,
                                                    m.idmedicamento AS idmed, m.codmedicamento, m.nombre, 
                                                    m.concentracion, m.idpactivo, m.idforma, m.idacciont, 
                                                    m.idunidad, 
                                                    pa.medicamento AS despactivo,                                                        
                                                    f.nombre AS desforma,
                                                    ate.nombre AS desaterapeutica,
                                                    un.abreviatura AS desunidad, 
                                                    p.pcompra, p.pcentral, p.psucursal                                           
                                                    FROM stock s 
                                                    INNER JOIN medicamento m ON s.idmedicamento = m.idmedicamento
                                                    INNER JOIN pactivo pa ON m.idpactivo = pa.id
                                                    INNER JOIN forma f ON m.idforma = f.idforma
                                                    INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
                                                    INNER JOIN unidades un ON m.idunidad = un.idunidad  
                                                    INNER JOIN precios p ON s.idmedicamento = p.idmedicamento                                                    
                                                    WHERE (idsuc = $suc AND saldo > 0)
                                                    ORDER BY idms ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' ' . $data['desforma'].'' . $data['concentracion'].' ' . $data['desunidad'];
                            if ($suc != 1) {
                                $data['preciounitario'] = $data['psucursal'];
                            } else{
                                $data['preciounitario'] = $data['pcentral'];
                            }
                            ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <?php echo $data['idms'];?>
                                    </td>                                   
                                    <td class="align-middle" style="font-size:12px">
                                        <?php echo $data['nombrecompleto'] ;?>
                                    </td>
                                    <td class="text-center align-middle" style="font-size:12px">
                                        <?php echo $data['fvs'] ;?>
                                    </td>                                    
                                    <td class="text-center align-middle" style="font-size:14px">
                                        <?php echo $data['preciounitario'] ;?>
                                    </td>
                                     <td class="text-center align-middle" style="font-size:12px">
                                        <?php echo $data['saldos'] ;?>
                                    </td>
                                   
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm btn-accion-n4"
                                            idp="<?php echo $data['idms'];?>" idmodal="#modventamedicamento"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['saldos'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            fv="<?php echo $data['fvs'] ;?>"><i class='fas fa-check'></i></button>

                                    </td>
                                    
                                </tr>
                                <?php }
					} ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

        <!-- MODAL APERTURA DE CAJA -->
        <!-- Modal -->
        <div class="modal fade" id="modcaja" tabindex="-1" aria-labelledby="modcajaLabel" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header bg-azul text-white">                  
                    <h5 class="h6 modal-title " id="modcajaLabel">APERTURA CAJA</h5>                
                </div>
                <div class="modal-body text-azul h6">
                    <strong>LA CAJA SE ENCUENTRA CERRADA! DEBE ABRIR CAJA PARA PODER REALIZAR LA VENTA</strong>                  
                </div>
                <div class="modal-footer">                
                    <a href="a_caja.php" type="button" class="btn btn-primary">Abrir Caja</a>
                </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Content Row -->
</div>
<!-- /.container-fluid -->

<!-- /anclaje -->
</div>

<?php include_once "includes/footer.php"; ?>