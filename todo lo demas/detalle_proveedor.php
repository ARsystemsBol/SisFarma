<?php
include "includes/header.php";
include "../conexion.php";


if (empty($_REQUEST['id'])) {
  header("Location: lista_proveedor.php");
  mysqli_close($conexion);
}
$idproveedor = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM proveedor WHERE codproveedor = $idproveedor");
mysqli_close($conexion);
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_proveedor.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $idproveedor = $data['codproveedor'];
    $proveedor = $data['proveedor'];
    $nitprov = $data['nit'];
    $nombrecon = $data['contacto'];
    $telefonocon = $data['telefono'];
    $emailcon = $data['mail'];
    $direccioncon = $data['direccion'];
    $cuentaprov = $data['nocuenta'];
    $bancoprov = $data['banco'];
    $tipocuentaprov = $data['tipocuenta'];
  }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">
	<!-- Page Heading -->	
<!-- 	<nav aria-label="breadcrumb">
  		<ol class="breadcrumb">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-info"></a></i></li>
			<li class="breadcrumb-item"><a href="lista_proveedor.php"><i class="fas fa-building text-info"></a></i></li>
    		<li class="breadcrumb-item"><a ><i class="fas fa-file text-info"></a></i></li>
            <li class="breadcrumb-item active" aria-current="page">Detalle Proveedor</li>
  		</ol>
</nav>	 -->
	<!-- Page Heading -->
	<div class="card"> 
    	<div class="card-header bg-azul text-white ">
      		<div class="d-sm-flex align-items-center justify-content-between">
        		<h1 class="h6 mb-0 text-uppercase">detalle - <strong class="text-info" style="font-size: 16px;"> <?php echo $proveedor;?> </strong></h1>        		
				<a href="lista_proveedor.php" class="btn btn-danger btn-sm"><i class="fas fa-arrow-left mr-1"></i>Regresar</a>				
      		</div>
    	</div>    
    <div class="card-body">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">            
            <div class="card-header bg-verde text-white">
						  DATOS GENERALES
            </div>
            <div class="card-body">
              <form class="row col-12 m-auto" action="" method="post" autocomplete="off">                 

                <!-- NOMBRE PROVEEDOR --> 
                <div class="col-md-12 mb-1">
                    <label for="proveedor" class="form-label">PROVEEDOR</label>
                    <input type="text" name="proveedor" id="proveedor" class="form-control form-control-sm" value="<?php echo $proveedor; ?>" disabled>
                </div>
                <!-- NIT PROVEEDOR --> 
                <div class="col-md-6 mb-3">
                    <label for="nitprov" class="form-label">NIT</label>
                    <input type="number" name="nitprov" id="nitprov" class="form-control form-control-sm" value="<?php echo $nitprov; ?>"disabled>
                </div>               
                <!-- NOMBRE CONTACTO--> 
                <div class="col-md-12 mb-2">
                    <label for="nombrecon" class="form-label">NOMBRE CONTACTO</label>
                    <input type="text" name="nombrecon" id="nombrecon" class="form-control form-control-sm"  value="<?php echo $nombrecon; ?>"disabled>
                </div>
                <!-- TELEFONO CONTACTO--> 
                <div class="col-md-6 mb-2">
                    <label for="telefonocon" class="form-label">TELÉFONO CONTACTO</label>
                    <input type="number" name="telefonocon" id="telefonocon" class="form-control form-control-sm"  value="<?php echo $telefonocon; ?>"disabled>
                </div>
                <!-- MAIL CONTACTO--> 
                <div class="col-md-6 mb-2">
                    <label for="emailcon" class="form-label">CORREO ELECTÓNICO</label>
                    <input type="mail" name="emailcon" id="emailcon" class="form-control form-control-sm" value="<?php echo $emailcon; ?>"disabled>
                </div>
                <!-- DIRECCION CONTACTO--> 
                <div class="col-md-7 mb-3">
                    <label for="direccioncon" class="form-label">DIRECCION</label>
                    <input type="text"  name="direccioncon" id="direccioncon" class="form-control form-control-sm" value="<?php echo $direccioncon; ?>"disabled>
                </div>
                <!-- NRO. CUENTA--> 
                <div class="col-md-6 mb-1">
                    <label for="cuentaprov" class="form-label">No. CUENTA</label>
                    <input type="text"  name="cuentaprov" id="cuentaprov" class="form-control form-control-sm" value="<?php echo $cuentaprov; ?>"disabled>
                </div>
                <!-- BANCO--> 
                <div class="col-md-6 mb-2">
                    <label for="bancoprov" class="form-label">NOMBRE BANCO</label>
                    <input type="text"  name="bancoprov" id="bancoprov" class="form-control form-control-sm" value="<?php echo $bancoprov; ?>"disabled>
                </div>
                <!-- TIPO DE CUENTA--> 
                <div class="col-md-6 mb-4">
                    <label for="tipocuentaprov" class="form-label">TIPO DE CUENTA</label>
                    <input type="text"  name="tipocuentaprov" id="tipocuentaprov" class="form-control form-control-sm" value="<?php echo $tipocuentaprov; ?>"disabled>
                </div>        
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card-header bg-verde text-white">
						  MEDICAMENTOS
          </div>
          <div class="card-body">
            <table class="table table-striped table-sm table-bordered" id="table">
              <thead class="thead-dark">
                <tr class="text-center align-middle" style="font-size:12px">
                  <th width="30">ID</th>						
                  <th width="300">NOMBRE </th> 
                  <?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
                  <th>-</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php
                include "../conexion.php";
                $query = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, 
                                    m.concentracion, m.stock,
                                    pa.medicamento AS despactivo,
                                    f.nombre AS desforma,
                                    ate.nombre AS desaterapeutica,
                                    un.abreviatura AS desunidad,
                                    u.descripcion AS desuso,
                                    p.lab AS deslaboratorio														
                                    FROM medicamento m
                                    INNER JOIN pactivo pa ON m.idpactivo = pa.id
                                    INNER JOIN forma f ON m.idforma = f.idforma
                                    INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
                                    INNER JOIN unidades un ON m.idunidad = un.idunidad
                                    INNER JOIN uso u ON pa.uso = u.id
                                    INNER JOIN proveedor p ON m.laboratorio = p.codproveedor
                                    WHERE m.laboratorio = $idproveedor
                                    ORDER BY m.nombre ASC");
                $result = mysqli_num_rows($query);
                if ($result > 0) {
                  while ($data = mysqli_fetch_assoc($query)) { 
                    $data['nombrecompleto'] = $data['nombre'].' ' . $data['desforma'].' ' . $data['concentracion'].' ' . $data['desunidad'];
                    ?>							
                    <tr>
                      <td width="30" class="text-center align-middle" style="font-size:14px"><?php echo $data['idmedicamento']; ?></td>								
                      <td width="300" class="align-middle" style="font-size:14px"><?php echo $data['nombrecompleto'];?></td>
                      <?php if (($_SESSION['rol'] == 1) || ($_SESSION['rol'] == 3)) { ?>
                      <td class="text-center align-middle" style="font-size:14px">
                        <a href="detalle_medicamento.php?id=<?php echo $data['idmedicamento']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye fa-sm" ></i></a>
                        <a href="editar_medicamento.php?id=<?php echo $data['idmedicamento']; ?>" class="btn btn-success btn-sm "><i class='fas fa-edit fa-sm'></i></a>
                        <?php if ($_SESSION['rol'] == 1)  { ?>
                        <form action="eliminar_medicamento.php?id=<?php echo $data['idmedicamento']; ?>" method="post" class="confirmar d-inline ">
                          <button class="btn btn-danger btn-sm " type="submit"><i class='fas fa-trash-alt fa-sm'></i> </button>
                        </form>
                        <?php } ?>
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