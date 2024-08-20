<?php
include_once "includes/header.php";
include "../conexion.php";


?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">
    <!-- Breadcrumb -->
  <!--   <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>
            <li class="breadcrumb-item"><a href="lista_traspasos.php"><i class="fas fa-sync-alt text-info"></a></i></li>
            <li class="breadcrumb-item"><i class="fas fa-sync-alt fa-sm text-info mr-2"></i><i
                    class="fas fa-save fa-sm text-info"></i></li>
            <li class="breadcrumb-item active" aria-current="page">Registro Traspaso</li>
        </ol>
    </nav> -->

    <!-- Page Heading -->
    <div class="card">
        <!-- Encabezado tarjeta -->
        <div class="card-header bg-azul text-white ">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h6 mb-0 text-uppercase">Traspaso / Devolución de medicamentos</h1>
                <a href="lista_traspasos.php" class="btn btn-danger btn-sm"><i
                        class="fas fa-arrow-left mr-1"></i>Regresar</a>
            </div>
        </div>
        <!-- Cuarpo tarjeta -->
        <div class="card-body">
            <!-- Content Row -->
            <div class="col-9 m-auto" id="alerta">
            </div>

            <!-- Contenedor de los datos a enviar -->
            <form class="row col-8 m-auto" action="" method="post" autocomplete="off">

                <!-- TIPO DE TRASPASO -->
                <div class="col-md-6 mb-2">
                    <label for="tipotraspaso-n1" style="font-size:13px" class="text-info">TIPO DE TRASPASO</label>
                    <select autofocus name="tipotraspaso-n1" id="tipotraspaso-n1" class="form-control form-control-sm">
                        <option value="0"></option>
                        <?php if (($_SESSION['idsuc'] == 1) ) { ?>
                        <option value="4">TRASPASO SUCURSAL</option>
                        <option value="2">DEVOLUCION PROVEEDOR</option>
                        <?php }	?>
                        <?php if (($_SESSION['idsuc'] != 1) ) { ?>
                        <option value="6">DEVOLUCIÓN SUCURSAL</option>
                        <?php }	?>
                    </select>
                    <!-- <a href="#" class="btn btn-danger btn-sm pr-2 pl-2 mb-2 mt-2" name ="btn_prueba" id="btn_prueba"><i class="fas fa-trash mr-2" ></i> PRUEBA</a>                       -->
                </div>

                <!-- ORIGEN -->
                <div class="col-md-6 mb-2">
                    <label for="sucorigen-n1" style="font-size:13px" class="text-info">ORIGEN</label>
                    <select name="sucorigen-n1" id="sucorigen-n1" class="form-control form-control-sm">

                        <option value="<?php echo $_SESSION['idsuc']; ?>">
                            <?php echo $_SESSION['sucursal']; ?>
                        </option>
                    </select>
                </div>

                <!-- DESTINO -->
                <div class="col-md-6 mb-2">
                    <label for="sucdestino-n1" style="font-size:13px" class="text-info">SUCURSAL - DESTINO</label>
                    <?php
                        $suco = $_SESSION['idsuc'];
                        $almc = $_SESSION['almacen'];
                            $query_sucorigen = mysqli_query($conexion, "SELECT idsucursal, nombre
                                                                        FROM sucursal 
                                                                        WHERE almacen != $almc
                                                                        ORDER BY idsucursal ASC");
                            $resultado_sucorigen = mysqli_num_rows($query_sucorigen);
                        ?>
                    <select name="sucdestino-n1" id="sucdestino-n1" class="form-control form-control-sm">

                        <?php
                            if ($resultado_sucorigen > 0) {
                             while ($sucorigen = mysqli_fetch_array($query_sucorigen)) {
                                // code...
                        ?>
                        <option value="<?php echo $sucorigen['idsucursal']; ?>">
                            <?php echo $sucorigen['nombre']; ?>
                        </option>
                        <?php } } 
                        ?>
                    </select>
                </div>

                <!-- PROVEEDOR -->
                <div class="col-md-6 mb-2">
                    <label for="idproveedor-n1" style="font-size:13px" class="text-info">SUCURSAL - DESTINO</label>
                    <?php                        
                            $query_proveedor = mysqli_query($conexion, "SELECT proveedor, codproveedor
                                                                        FROM proveedor                                                                         
                                                                        ORDER BY codproveedor ASC");
                            $resultado_proveedor = mysqli_num_rows($query_proveedor);
                        ?>
                    <select name="idproveedor-n1" id="idproveedor-n1" class="form-control form-control-sm" disabled>
                        <option value=0></option>
                        <?php
                            if ($resultado_proveedor > 0) {
                             while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                                // code...
                        ?>
                        <option value="<?php echo $proveedor['codproveedor']; ?>">
                            <?php echo $proveedor['proveedor']; ?>
                        </option>
                        <?php } } mysqli_close($conexion);
                        ?>
                    </select>
                </div>

                <!-- NOTA TRASPASO  -->
                <div class="col-md-6 mb-4">
                    <label for="descriptraspaso-n1" style="font-size:13px" class="text-info">NOTA TRASPASO <span
                            class="text-danger">* Campo obligatorio</span></label>
                    <input type="text" name="descriptraspaso-n1" id="descriptraspaso-n1"
                        class="form-control form-control-sm" required>
                </div>

                <!-- BOTONES-->
                <!-- Button trigger modal -->
                <div class="row contaniner">
                    <div class="col-md-4 mb-4">
                        <!-- BOTON TRASPASO DE ALMACEN A SUCURSAL-->
                        <button style="display: none;" name="btn-tn1" id="btn-tn1" type="button"
                            class="btn btn-success btn-sm pr-3 pl-3" data-bs-toggle="modal"
                            data-bs-target="#buscarmed-n1">
                            <i class="fas fa-search  mr-2"></i> Buscar Medicamento...
                        </button>
                        <!-- BOTON DEVOLUCION ALMACEN-->
                        <button style="display: none;" name="btn-tn2" id="btn-tn2" type="button"
                            class="btn btn-warning btn-sm pr-3 pl-3" data-bs-toggle="modal"
                            data-bs-target="#buscarmed-n2">
                            <i class="fas fa-search  mr-2"></i> Buscar Medicamento...
                        </button>
                        <!-- BOTON DEVOLUCION PROVEEDOR-->
                        <button style="display: none;" name="btn-tn3" id="btn-tn3" type="button"
                            class="btn btn-primary btn-sm pr-3 pl-3" data-bs-toggle="modal"
                            data-bs-target="#buscarmed-n3">
                            <i class="fas fa-search  mr-2"></i> Buscar Medicamento...
                        </button>
                    </div>
                </div>


                <!-- Botones de acccion del traspaso -->
                <div class="row container-fluid">
                    <div class="col-lg-6">
                        <!-- Botones traspaso de almacen a sucursal -->
                        <div id="acciones_venta" class="form-group">
                            <a href="#" style="display: none;" class="btn btn-danger btn-sm pr-2 pl-2"
                                name="btn_anular-n1" id="btn_anular-n1"><i class="fas fa-trash mr-2"></i> Cancelar</a>
                            <a href="#" style="display: none;" class="btn btn-primary btn-sm" name="btn_procesar-n1"
                                id="btn_procesar-n1"><i class="fas fa-save"></i> Procesar</a>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Contenedor del detalle -->
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead class="thead-dark">
                        <tr class="text-center" style="font-size:12px">
                            <th width="30px">Id.</th>
                            <th width="50px">Descripción</th>
                            <th width="50">Stock</th>
                            <th width="100px">F. Vencimiento</th>
                            <th width="50px">Cant.</th>
                            <th width="50px">P./Compra</th>
                            <th width="50px">P./Venta</th>
                            <th>Sel.</th>
                        </tr>
                        <tr>
                            <td width="30px" style="font-size:14px" class="text-center align-middle" name="txt_idmed-n1"
                                id="txt_idmed-n1"></td>
                            <td style="font-size:12px" class="align-middle" id="txt_des-n1" name="txt_des-n1">-</td>
                            <td style="font-size:14px" class="text-center align-middle" id="txt_stock-n1"
                                name="txt_stock-n1">0</td>
                            <td><input class="form-control form-control-sm" type="date" name="txt_fecven-n1"
                                    id="txt_fecven-n1" disabled></td>
                            <td><input class="form-control form-control-sm" type="number" name="txt_cant-n1"
                                    id="txt_cant-n1" disabled></td>
                            <td><input class="form-control form-control-sm" type="number" name="txt_prec-n1"
                                    id="txt_prec-n1" disabled></td>
                            <td><input class="form-control form-control-sm" type="number" name="txt_prev-n1"
                                    id="txt_prev-n1"></td>
                            <td class="text-center"><a href="#" name="btn_agredet-n1" id="btn_agredet-n1"
                                    class="btn btn-outline-success btn-sm" style="display: none;"><i
                                        class="fas fa-arrow-right mr-1"></i></a></td>
                        </tr>
                        <tr class="text-center align-middle" style="font-size:12px">
                            <th width="100px">Id.</th>
                            <th width="400px">Descripción</th>
                            <th width="100px">Cantidad</th>
                            <th width="100px">F. Ven.</th>
                            <th width="100px">Precio Venta</th>
                            <th width="100px">Sub Total</th>
                            <th width="100px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="detalle_traspaso-n1">
                        <!-- Contenido ajax -->

                    </tbody>

                    <tfoot id="detalle_totales-n1">
                        <!-- Contenido ajax -->
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


    <!-- ---------------------------------------------------------------------------------------------------------- -->
    <!-- Modal traspaso almacen a sucursal CODIGO = 4-->
    <div class="modal fade" id="buscarmed-n1" tabindex="-1" aria-labelledby="buscarmed-n1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="buscarmed-n1">Lista Stock de Sucursal</h5>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm " id="table">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th style="font-size:10px" width="30px">ID</th>                                    
                                    <th style="font-size:10px" width="400px">DESCRIPCION</th>
                                    <th style="font-size:10px" width="100px">F. VENCIMIENTO</th>
                                    <th style="font-size:10px" width="50px">P.COMPRA.</th>
                                    <th style="font-size:10px" width="50px">P.VENTA.</th>
                                    <th style="font-size:10px" width="50px">STOCK</th>
                                    <th style="font-size:10px" width="30px"> Sel.</th>
                            </thead>
                            <tbody>
                                <?php
					include "../conexion.php";
                    $suc = $_SESSION['idsuc'];
                    $valor = '';
                    if($suc != 1){
                        $valor = 4;
                    }
                    else{
                        $valor = 1;
                    }
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
                                $data['preciounitario'] = $data['pcentral'];
                            }else{
                                $data['preciounitario'] = $data['psucursal'];
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
                                        <?php echo $data['pcompra'] ;?>
                                    </td>
                                    <td class="text-center align-middle" style="font-size:14px">
                                        <?php echo $data['preciounitario'] ;?>
                                    </td>
                                    <td class="text-center align-middle" style="font-size:12px">
                                        <?php echo $data['saldos'] ;?>
                                    </td>
                                    <?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)){ ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm btn-accion-n1"
                                            idp="<?php echo $data['idms'];?>" 
                                            idmodal="#buscarmed-n1"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['saldos'] ;?>"
                                            pc="<?php echo $data['pcompra'] ;?>"
                                            pv="<?php echo $data['preciounitario'] ;?>"
                                            fv="<?php echo $data['fvs'] ;?>"><i class='fas fa-check'></i>
                                        </button>
                                    </td>
                                    <?php } ?>
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
    <!-- Modal devolución a almacen central CODIGO = 6-->
    <div class="modal fade" id="buscarmed-n2" tabindex="-1" aria-labelledby="buscarmed-n2" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="buscarmed-n2">Lista Stock de Sucursal / Devolución </h5>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm " id="table">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th style="font-size:10px" width="30px">ID</th>
                                    <th style="font-size:10px" width="50px">COD.</th>
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
                                                    m.idunidad, m.preciounitario,
                                                    pa.medicamento AS despactivo,                                                        
                                                    f.nombre AS desforma,
                                                    ate.nombre AS desaterapeutica,
                                                    un.abreviatura AS desunidad                                                        
                                                    FROM stock s 
                                                    INNER JOIN medicamento m ON s.idmedicamento = m.idmedicamento
                                                    INNER JOIN pactivo pa ON m.idpactivo = pa.id
                                                    INNER JOIN forma f ON m.idforma = f.idforma
                                                    INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
                                                    INNER JOIN unidades un ON m.idunidad = un.idunidad                                               
                                                    WHERE (idsuc = $suc AND saldo > 0)
                                                    ORDER BY idms ASC");
					$result = mysqli_num_rows($query);
					if ($result > 0) {
						while ($data = mysqli_fetch_assoc($query)) { 
                            $data['nombrecompleto'] = $data['nombre'].' ' . $data['desforma'].'' . $data['concentracion'].' ' . $data['desunidad'];
                            ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <?php echo $data['idms'];?>
                                    </td>
                                    <td style="font-size:10px" class="text-center align-middle">
                                        <?php echo $data['codmedicamento'];?>
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
                                    <?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)){ ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm btn-accion-n2"
                                            idp="<?php echo $data['idms'];?>" idmodal="#buscarmed-n2"
                                            des="<?php echo $data['nombrecompleto'] ;?>"
                                            st="<?php echo $data['saldos'] ;?>"
                                            pc="<?php echo $data['preciounitario'] ;?>"
                                            fv="<?php echo $data['fvs'] ;?>"><i class='fas fa-check'></i></button>

                                    </td>
                                    <?php } ?>
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



</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>