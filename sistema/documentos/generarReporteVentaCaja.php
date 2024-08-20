<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
	# Incluyendo CONECCION #
	include "../../conexion.php";
	if(empty($_REQUEST['f']))
	{
		echo "No es posible generar la nota de venta";
	}else{

		#Optenemos los datos requeridos para el reporte =
			
		$fecha = $_REQUEST['f'];		
		$idcaja = $_REQUEST['idc'];
		$iduser = $_REQUEST['idu'];
		
		# DATOS DE LA EMPRESA	
		$consulta = mysqli_query($conexion, "SELECT * FROM configuracion");
		$resultado = mysqli_fetch_assoc($consulta);
		# DATOS DE DETALLE DE CAJA CERRADA TABLA CAJAS
		$cajas = mysqli_query($conexion, "SELECT * FROM cajas WHERE idusuario = $iduser AND fecha = '$fecha'");
		# DATOS DE LA CABECERA DE LA CAJA PARA EL REPORTE
		$caja = mysqli_query($conexion, "SELECT * FROM caja WHERE idusuario = $iduser");
		$result_caja = mysqli_fetch_assoc($caja);
		$idsuc = $result_caja['idsucursal'];
		# DATOS de LA SUCURSAL
		$sucursal = mysqli_query($conexion, "SELECT * FROM sucursal WHERE idsucursal = $idsuc");
		$result_sucursal = mysqli_fetch_assoc($sucursal);		
		# DATOS VENTAS		
	 	$detalleventa = mysqli_query($conexion, "SELECT f.nofactura, f.codcliente, f.totalfactura,
												cl.nit_ci, cl.nombre
												FROM facturas f
												INNER JOIN cliente cl ON f.codcliente = cl.idcliente 
												WHERE  usuario = $iduser AND fecha = '$fecha'"); 
		# SUMA DE LAS FACTURAS DE LA FECHA
		$totalventasdia = mysqli_query($conexion, "SELECT SUM(totalfactura) AS tfac FROM facturas WHERE  usuario = $iduser AND fecha = '$fecha'");
		$resultado_totalventasdia = mysqli_fetch_assoc($totalventasdia);
		$totalfacturas = $resultado_totalventasdia['tfac'];
	# Incluyendo librerias necesarias #
	require_once '../factura/fpdf/fpdf.php';
	
	
		#Formato del Documento (hoja)
			$pdf = new FPDF('P','mm','Letter');
			$pdf->AddPage();
			$pdf->SetMargins(17, 17, 17);
			$pdf->Ln(9);
		#

		#Titulo
			$pdf->SetFont('Arial','B',18);
			$pdf->SetTextColor(20,100,210);
			$pdf->Cell(70,5,utf8_decode(strtoupper("REPORTE DE VENTAS")),0,0,'C');
			$pdf->Ln(10);
		#

		#Datos de la empresa 
			#Imagen Logo
			$pdf->Image('../factura/img/farmacia2.jpg',185,10,20,20,'JPG');

		#	

		#Datos de la emision			
			#FECHA
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(25,7,utf8_decode("FECHA "),0,0);
			$pdf->Cell(10,7,utf8_decode("del: "),0,0);
			$pdf->SetTextColor(97,97,97);			
			$pdf->Cell(25,7,$fecha,0,0,'L');	
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(10,7,utf8_decode("al: "),0,0);
			$pdf->SetTextColor(97,97,97);			
			$pdf->Cell(50,7,$fecha,0,0,'L');		

			#CAJERO Y NRO DE NOTA DE INGRESO
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(25,7,utf8_decode("USUARIO: "),0,0,'L');
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(100,7,$_SESSION['nombre'],0,0,'L');
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(97,97,97);
			// $pdf->Cell(35,7,$codcaja,0,0,'C');
			$pdf->Ln(7);			
		
			
			#SUCURSAL Y CAJA
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(25,7,utf8_decode("SUCURSAL:"),0,0);
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(95,7,utf8_decode($result_sucursal['nombre']),0,0,'L');
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(25,7,utf8_decode("Nº DE CAJA: "),0,0,'L');
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(40,7,utf8_decode($result_caja['nombrecaja']),0,0,'L');			
			$pdf->Ln(10);
		#	
		# DATOS DE CIERRE DE LA CAJA
			#SUBTITULO SECCION
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(15, 5, "DETALLE CIERRE DE CAJA: ", 0, 0, 'L');
			$pdf->Ln(7);
			# Tabla Datos Cja
			$pdf->SetFont('Arial','',10);
	
			$pdf->Cell(10,5,utf8_decode("Id."),1,0,'C',true);
			$pdf->Cell(35,5,utf8_decode(""),1,0,'C',true);			
			$pdf->Cell(20,5,utf8_decode("apertura"),1,0,'C',true);
			$pdf->Cell(20,5,utf8_decode("Ventas"),1,0,'C',true);		
			$pdf->Cell(20,5,utf8_decode("Egresos"),1,0,'C',true);
			$pdf->Cell(20,5,utf8_decode("Total"),1,0,'C',true);
			$pdf->Cell(20,5,utf8_decode("Efectivo"),1,0,'C',true);
			$pdf->Cell(20,5,utf8_decode("Dif."),1,0,'C',true);
			$pdf->Cell(20,5,utf8_decode("Obs."),1,0,'C',true);
			$pdf->Ln(5);
			$pdf->SetTextColor(39,39,51);

			/*----------  Detalles de la tabla  ----------*/
			 while ($row = mysqli_fetch_assoc($cajas)) {
				// $nombreCompleto = $row['nombre'];
				$pdf->SetFont('Arial','',8);
				$pdf->SetDrawColor(56,56,56);				
				$pdf->Cell(10,6,$row['id'],'LB',0,'C');
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(35,6,$row['responsable'],'LRB',0,'L');				
				$pdf->Cell(20,6,$row['apertura'],'LRB',0,'C');
				$pdf->Cell(20,6,$row['ventas'],'LRB',0,'C');
				$pdf->Cell(20,6,$row['egresos'],'LRB',0,'C');
				$pdf->Cell(20,6,$row['total'],'LRB',0,'C');
				$pdf->Cell(20,6,$row['efectivo'],'LRB',0,'C');
				$pdf->Cell(20,6,$row['diferencia'],'LRB',0,'C');
				$pdf->Cell(20,6,$row['observaciones'],'LRB',0,'C');
				$pdf->Ln();				
			} 
			
			$pdf->Ln(7);	
			/*----------  Fin Detalles de la tabla  ----------*/
		#

		# DETALLE VENTAS
			#SUBTITULO SECCION
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(15, 5, "DETALLE VENTAS: ", 0, 0, 'L');
			$pdf->Ln(7);
			# Tabla Datos Cja
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(39,67,123);
			$pdf->SetDrawColor(56,56,56);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(30,7,utf8_decode("Nro. Factura"),1,0,'C',true);
			$pdf->Cell(20,7,utf8_decode("Nit"),1,0,'C',true);			
			$pdf->Cell(100,7,utf8_decode("Razon Social"),1,0,'C',true);			
			$pdf->Cell(35,7,utf8_decode("Sub-total"),1,0,'C',true);			
			$pdf->Ln(7);
			$pdf->SetTextColor(39,39,51);

			/*----------  Detalles de la tabla  ----------*/
			 while ($row = mysqli_fetch_assoc($detalleventa)) {
				// $nombreCompleto = $row['nombre'];
				$pdf->SetFont('Arial','',8);
				$pdf->SetDrawColor(56,56,56);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(30,6,$row['nofactura'],'LRB',0,'C');				
				$pdf->Cell(20,6,$row['nit_ci'],'LRB',0,'L');
				$pdf->Cell(100,6,$row['nombre'],'LRB',0,'L');
				$pdf->Cell(35,6,$row['totalfactura'],'LRB',0,'C');				
				$pdf->Ln();				
			} 
			
			$pdf->Ln(10);	
			/*----------  Fin Detalles de la tabla  ----------*/
		#



	$pdf->SetTextColor(39,39,51);	
	$pdf->SetFont('Arial','B',12);

	
	# Impuestos & totales #
	$pdf->Cell(100,7,utf8_decode(''),0,0,'C');
	$pdf->Cell(15,7,utf8_decode(''),0,0,'C');
	$pdf->Cell(32,7,utf8_decode("TOTAL Bs."),'T',0,'C');
	$pdf->SetTextColor(4,91,98);	
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(34,7,($totalfacturas),'T',0,'C');		
	
	$pdf->Ln(12);


	# Nombre del archivo PDF #
	$pdf->Output("I","Reporte_Cierre_Ventas__Caja_Nro.$idcaja.pdf",true);	
	
	}

?>
