<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid pt-5 mt-5">

	<!-- Page breadcrumb -->	
	<nav aria-label="breadcrumb">
  		<ol class="breadcrumb bg-azul">
    		<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home text-white"></a></i></li>	        	
        	<li class="breadcrumb-item"><a href="configuracion.php"><i class="fas fa-cogs text-white"></a></i></li>			
    		<li class="breadcrumb-item active text-gray-400" aria-current="page">Configuración - Farmacia</li>
  		</ol>
  	</nav>	
	
	<div class="row">
		<div class="col-lg-6">
			<!-- CARD INFORMACION PERSONAL-->
			<div class="card mb-4">
				<div class="card-header bg-azul text-white">
					Información Personal
				</div>
				<div class="card-body">
					<div class="form-group">
						<div class="row">
							<div class="col-3">
								<p class="mb-2" >Nombre:</p></div> 
							<div class="col-9">
								<p class="mb-2" ><strong class="h6 text-gray" style="font-weight: bold;"><?php echo $_SESSION['nombre']; ?></strong></p>
							</div>
						</div>
						<div class="row">
							<div class="col-3">
								<p class="mb-2" >Correo:</p></div> 
							<div class="col-9">
								<p class="mb-2" ><strong class="h6 text-gray" style="font-weight: bold;"><?php echo $_SESSION['email']; ?></strong></p>
							</div>
						</div>
						<div class="row">
							<div class="col-3">
								<p class="mb-2" >Rol:</p></div> 
							<div class="col-9">
								<p class="mb-2" ><strong class="h6 text-gray" style="font-weight: bold;"><?php echo $_SESSION['rol_name']; ?></strong></p>
							</div>
						</div>
						<div class="row">
							<div class="col-3">
								<p class="mb-2" >Usuario:</p></div> 
							<div class="col-9">
								<p class="mb-2" ><strong class="h6 text-gray" style="font-weight: bold;"><?php echo $_SESSION['user']; ?></strong></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- CARD CAMBIO DE CONTRASEÑA-->
			<div class="card mb-4">
				<div class="card-header bg-azul text-white">
					Cambiar Contraseña
				</div>
				<div class="card-body">
					<ul class="list-group">						
						<form action="" method=" post" name="frmChangePass" id="frmChangePass" class="p-3">
							<div class="form-group">								
								<input type="password" name="actual" id="actual" placeholder="Clave Actual" required class="form-control form-control-sm">
							</div>
							<div class="form-group">								
								<input type="password" name="nueva" id="nueva" placeholder="Nueva Clave" required class="form-control form-control-sm">
							</div>
							<div class="form-group">								
								<input type="password" name="confirmar" id="confirmar" placeholder="Confirmar clave" required class="form-control form-control-sm">
							</div>
								<div class="alertChangePass" style="display:none;">
							</div>
							<div>
								<button type="submit" class="btn btn-success btn-sm btnChangePass"><i class="fas fa-edit mr-2"></i>Cambiar Contraseña</button>
							</div>
						</form>
					</ul>
				</div>				
			</div>
		</div>


		<!-- CARD DATOS DE EMPRESA - VISTA ADMINISTRADOR-->
			<?php if ($_SESSION['rol'] == 1) { ?>
			<div class="col-lg-6">				
				<div class="card">
					<div class="card-header bg-azul text-white">
						Datos de la Empresa
					</div>
					<div class="card-body">
						<form action="empresa.php" method="post" id="frmEmpresa" >
							<div class="form-group">
								<label>NIT:</label>
								<input autofocus type="number" name="txtDni" value="<?php echo $nit; ?>" id="txtDni" placeholder="NIT de la Empresa" required class="form-control form-control-sm">
							</div>
							<div class="form-group">
								<label>Nombre:</label>
								<input type="text" name="txtNombre" class="form-control form-control-sm" value="<?php echo $nombre_empresa; ?>" id="txtNombre" placeholder="Nombre de la Empresa" required class="form-control form-control-sm">
							</div>
							<div class="form-group">
								<label>Razon Social:</label>
								<input type="text" name="txtRSocial" class="form-control form-control-sm" value="<?php echo $razonSocial; ?>" id="txtRSocial" placeholder="Razon Social de la Empresa">
							</div>
							<div class="form-group">
								<label>Teléfono:</label>
								<input type="number" name="txtTelEmpresa" class="form-control form-control-sm" value="<?php echo $telEmpresa; ?>" id="txtTelEmpresa" placeholder="teléfono de la Empresa" required>
							</div>
							<div class="form-group">
								<label>Correo Electrónico:</label>
								<input type="email" name="txtEmailEmpresa" class="form-control form-control-sm" value="<?php echo $emailEmpresa; ?>" id="txtEmailEmpresa" placeholder="Correo de la Empresa" required>
							</div>
							<div class="form-group">
								<label>Dirección:</label>
								<input type="text" name="txtDirEmpresa" class="form-control form-control-sm" value="<?php echo $dirEmpresa; ?>" id="txtDirEmpresa" placeholder="Dirreción de la Empresa" required>
							</div>
							<div class="form-group">
								<label>IVA (%):</label>
								<input type="text" name="txtIgv" class="form-control form-control-sm" value="<?php echo $iva; ?>" id="txtIgv" placeholder="IVA de la Empresa" required>
							</div>
							<?php echo isset($alert) ? $alert : ''; ?>
							<div>
								<button type="submit" class="btn btn-primary btn-sm btnChangePass"><i class="fas fa-save mr-2"></i> Guardar Datos</button>
							</div>

						</form>
					</div>
				</div>
			</div>
				
		<!-- CARD DATOS DE EMPRESA - VISTA ADMINISTRADOR-->	
			<?php } else { ?>
			<div class="col-lg-6">
				<!-- CARD DATOS DE EMPRESA - VISTA SUPERVISOR Y VENDEDOR-->
				<div class="card">
					<div class="card-header bg-azul text-white">
						Datos de la Empresa
					</div>
					<div class="card-body">
						<div class="p-3">
							<div class="form-group">
								<strong>NIT:</strong>
								<h6><?php echo $nit; ?></h6>
							</div>
							<div class="form-group">
								<strong>Nombre:</strong>
								<h6><?php echo $nombre_empresa; ?></h6>
							</div>
							<div class="form-group">
								<strong>Razon Social:</strong>
								<h6><?php echo $razonSocial; ?></h6>
							</div>
							<div class="form-group">
								<strong>Teléfono:</strong>
								<?php echo $telEmpresa; ?>
							</div>
							<div class="form-group">
								<strong>Correo Electrónico:</strong>
								<h6><?php echo $emailEmpresa; ?></h6>
							</div>
							<div class="form-group">
								<strong>Dirección:</strong>
								<h6><?php echo $dirEmpresa; ?></h6>
							</div>
							<div class="form-group">
								<strong>IVA (%):</strong>
								<h6><?php echo $iva; ?></h6>
							</div>

						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<?php include_once "includes/footer.php"; ?>