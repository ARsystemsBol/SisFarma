<?php include_once "includes/header.php"; 
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['montoapertura'])) {

        $alert = '<div class="alert alert-danger" role="alert">
                       INGRESE UN MONTO DE APERTURA
                    </div>';
    } else {
        $montoapertura = $_POST['montoapertura']; 
        $token = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];
        $fecha_actual = date("Y-m-d");       
        $nombre = $_SESSION['nombre'];
        $idcaja = $_SESSION['idcaja'];
        $query = mysqli_query($conexion, "SELECT * FROM cajas WHERE fecha = '$fecha_actual' AND idusuario = $usuario");
        $result = mysqli_fetch_array($query);
       

        if ($result > 0) {
            $alert = '<div class="alert alert-danger" role="alert">
                       YA EXISTE UN REGISTRO DE CAJA PARA EL DIA DE HOY
                    </div>';
        }else{

            $query_update= mysqli_query($conexion,"UPDATE caja SET estado = 1 WHERE idusuario = $usuario");        

            $query_insert = mysqli_query($conexion,"INSERT INTO cajas(responsable , apertura, total, idcaja, idusuario ) 
                                                    VALUES ('$nombre', $montoapertura, $montoapertura,$idcaja,$usuario)");
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
                            CAJA APERTURADA EXITOSAMENTE
                        </div>';
                    
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                        ERROR AL ABRIR LA CAJA
                        </div>';
            }
        }
    }
    
}


$usuario = $_SESSION['idUser'];

$detalle = "";
$query = mysqli_query($conexion, "SELECT estado FROM caja WHERE idusuario = $usuario");
$result = mysqli_num_rows($query);
$data = mysqli_fetch_assoc($query);
$estado = $data['estado'];



if ($estado == 2) {
    # DETALLE PARA ABRIR CAJA
    $detalle = '
    <div  class="col-lg-8 m-auto" id="cajacerrada" name="cajacerrada">
    <div class="card mb-3">
    <!-- encabezado card-lote -->
    <div class="card-header bg-azul text-white ">
        <div class="d-sm-flex align-items-center justify-content-between mb-1">
            <h1 class="h6 mb-0 text-uppercase">Abrir Caja</h1>
            <a href="registro_ventas.php" class="btn btn-danger btn-sm">Regresar</a>
        </div>
    </div>

    <!-- cuerpo card-lote -->
    <div class="card-body">
        <form class="row col-12 m-auto" action="" method="post" autocomplete="off">
            <!-- DESCRIPCION -->
            <div class="col-md-12 mb-3">
                <label for="montoapertura" class="form-label">Monto de apertura Bs.<span
                        class="text-danger fs-6 fst-italic ml-2">*</span></label>
                <input autofocus type="number" class="form-control form-control-sm" name="montoapertura"
                    id="montoapertura">
            </div>

            <div class="row col-12">
                <div class="col-lg-3">                
                    <input type="submit" value="Registrar" class="btn btn-success ml-3">              
                </div>
            </div>
        </form>
    </div>
    </div>
</div>';
} else if($estado == 1){
   

    $fecha_actual = date("Y-m-d");  
    $query1 = mysqli_query($conexion, "SELECT * FROM cajas where fecha = CURRENT_DATE AND idusuario = $usuario");
    $result1 = mysqli_num_rows($query1);
    if ($result1 > 0)  {
        $data_cajas = mysqli_fetch_assoc($query1);
       $total = $data_cajas['apertura'] + $data_cajas['ventas'] - $data_cajas['egresos'];       
       $total = number_format($total, 2, '.', '');
    }
   
    # DETALLE PARA VER LA CAJA ABIERTA
    $detalle = '<div  class="col-lg-10 m-auto" id="cajaabierta" name="cajaabierta">
    <div class="card mb-3">
        <!-- encabezado card-lote -->
        <div class="card-header bg-azul text-white ">
            <div class="d-sm-flex align-items-center justify-content-between mb-1">
                <h1 class="h6 mb-0 text-uppercase">Detalle caja</h1>
                <a href="registro_ventas.php" class="btn btn-danger btn-sm">Regresar</a>
            </div>
        </div>

        <!-- cuerpo card-lote -->
        <div class="card-body">
            <form class="row col-12 m-auto" action="" method="post" autocomplete="off">
                <!-- DESCRIPCION -->
                <div class="col-md-12 mb-3">
                     <!-- DATOS BÁSICOS -->  
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-6">
                            <p class="mb-2" >ID CAJA:</p>
                        </div> 
                        <div class="col-6">  
                        <input disabled type="number"  value='.$data_cajas['id'].' style="font-weight: bold;" class="form-control  h6 text-dark" name="idcajac" id="idcajac">
                            <input hidden disabled type="date"  value='.$fecha_actual.' style="font-weight: bold;" class="form-control  h6 text-dark" name="fa" id="fa">
                            <input hidden disabled type="number"  value='.$_SESSION['idUser'].' style="font-weight: bold;" class="form-control  h6 text-dark" name="idu" id="idu">                                  
                            <input hidden disabled type="number"  value='.$_SESSION['idcaja'].' style="font-weight: bold;" class="form-control  h6 text-dark" name="idc" id="idc">
                        </div>
                            
                            <div class="col-6">
                                <p class="mb-2" >RESPONSABLE DE CAJA:</p>
                            </div> 
                            <div class="col-6">                                    
                                <input disabled type="text"  value='.$data_cajas['responsable'].' style="font-weight: bold;" class="form-control  h6 text-dark" name="responsablecaja" id="responsablecaja">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-2" >Monto de Apertura:</p>
                            </div> 
                            <div class="col-6">
                                <input disabled type="number"  value="'. $data_cajas['apertura'].'" style="font-weight: bold;" class="form-control  h6 text-dark" name="montoapertura" id="montoapertura">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-2" >Ventas:</p>
                            </div> 
                            <div class="col-6">
                                <input disabled type="number"  value="'. $data_cajas['ventas'].'" style="font-weight: bold;" class="form-control  h6 text-dark" name="ventascaja" id="ventascaja">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-2" >Egresos:</p> 
                            </div> 
                            <div class="col-6">  
                                <input disabled type="number"  value="'. $data_cajas['egresos'].'" style="font-weight: bold;" class="form-control  h6 text-dark" name="egresoscaja" id="egresoscaja">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-2" >TOTAL:</p>
                            </div> 
                            <div class="col-6">
                            <input disabled type="number"  value="'. $total.'" style="font-weight: bold;" class="form-control  h6 text-dark" name="totalcaja" id="totalcaja">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                            <p class="mb-2" >EFECTIVO EN CAJA:</p>
                            </div> 
                            <div class="col-6">
                            <input  type="number"  value="0" style="font-weight: bold;" class="form-control  h6 text-dark" name="efectivocaja" id="efectivocaja">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                            <p class="mb-2" >DIFERENCIA:</p>
                            </div> 
                            <div class="col-6">
                            <input  type="number"  value="" style="font-weight: bold;" class="form-control  h6 text-dark" name="difcaja" id="difcaja" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                            <p class="mb-2" >Observaciones:</p>
                            </div> 
                            <div class="col-6">
                            <input  type="text"  value="" style="font-weight: bold;" class="form-control  h6 text-dark" name="detallecierre" id="detallecierre" disabled>
                            </div>
                        </div>
                    </div>   
                </div>

                <div class="row col-12">
                    <div class="col-lg-12">
                        <!-- Button trigger modal -->                        
                        <div class="col-lg-4"> 
                            <a href="#" class="btn btn-success btn-sm pl-3 pr-3" name="cerrarcaja"
                                    id="cerrarcaja"><i class="fas fa-save mr-2"></i> Cerrar</a>              
                                  
                        </div>                                         
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>';
}


?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">
    <!-- Page breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>
            <li class="breadcrumb-item"><a href="ac_caja.php"><i class="fas fa-cash-register text-info"></a></i></li>
            <li class="breadcrumb-item active" aria-current="page">Apertura y Cierre de Caja</li>
        </ol>
    </nav>
    <!-- End Page breadcrumb -->

    <!-- APERTURA Y CIERRE DE CAJA  -->
    
    <div class="row">
        <div class ="col-9 m-auto">
            <?php echo isset($alert) ? $alert : ''; ?>
        </div> 
        <div  class="col-lg-8 m-auto"  id="cajacerrada" name="cajacerrada">
           <?php echo $detalle?>
        </div>     

    </div>
    <!-- LISTA DE CAJAS  -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <!-- encabezado card-lote -->
                <div class="card-header bg-verde text-white ">
                    <div class="d-sm-flex align-items-center justify-content-between mb-1">
                        <h1 class="h6 mb-0 text-uppercase">Detalle Cajas</h1> 
                    </div>                      
                </div>

                <!-- cuerpo card-lote -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-bordered" id="table">
                        <thead class="thead-dark text-center">
                            <tr class="text-center aligin-middle" style="font-size:12px;">
                            <th>ID</th>
                            <th>FECHA</th>
                            <th>AP.</th>
                            <th>VENTAS</th>	
                            <th>EGRESOS</th>	
                            <th>TOTAL</th>	
                            <th>EFECTIVO</th>	
                            <th>DIF.</th>	
                            <th>OBS.</th>
                            <th>ESTADO</th>	
                            <th>IMPRIMIR</th>                            
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";
                            $idcaja = $_SESSION['idcaja'];
                            $iduser =  $_SESSION['idUser'];
                            $query = mysqli_query($conexion, "SELECT c.id, c.fecha, c.apertura, c.ventas, c.egresos, c.efectivo,
                                                                c.total, c.diferencia, c.observaciones, c.idestado AS idstatus,
                                                                ec.descripcion AS descaja
                                                                FROM cajas c 
                                                                INNER JOIN estado_caja ec ON c.idestado = ec.id
                                                                WHERE c.id = $idcaja");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <tr style="font-size:14px;">
                                <td class="text-center"><?php echo $data['id']; ?></td>
                                <td><?php echo $data['fecha']; ?></td>
                                <td class="text-center"><?php echo $data['apertura']; ?></td>
                                <td class="text-center"><?php echo $data['ventas']; ?></td>
                                <td class="text-center"><?php echo $data['egresos']; ?></td>
                                <td class="text-center"><?php echo $data['total']; ?></td>
                                <td class="text-center"><?php echo $data['efectivo']; ?></td>
                                <td class="text-center"><?php echo $data['diferencia']; ?></td>
                                <td class="text-center"><?php echo $data['observaciones']; ?></td>
                                <td class="text-center"><?php echo $data['descaja']; ?></td>
                               
                                <td class="text-center">
                                <?php if ( $data['idstatus'] == 2){ ?>                                  
                                    <button type="button" class="btn btn-primary btn-sm view_caja" id="<?php echo $data['id'];?>"><i class="fa fa-print"></i> </button>
                                    <button type="button" class="btn btn-success btn-sm cierre_ventas" f="<?php echo $data['fecha'];?>" idu="<?php echo $iduser;?>" idc="<?php echo $idcaja;?>"><i class="fa fa-file"></i> </button>  
                                    <?php } ?>
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

</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->


<?php include_once "includes/footer.php"; ?>