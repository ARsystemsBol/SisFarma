<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
	# Incluyendo CONECCION #
	include "../../conexion.php";
	if(empty($_REQUEST['id']))
	{
		echo "No es posible generar la nota de venta";
	}else{

		#Optenemos los datos requeridos para el reporte =
			
		$codcaja = $_REQUEST['id'];
		
		# DATOS DE LA EMPRESA	
		$consulta = mysqli_query($conexion, "SELECT * FROM configuracion");
		$resultado = mysqli_fetch_assoc($consulta);
		# DETALLE DE LA CAJA CERRADA
		$cajas = mysqli_query($conexion, "SELECT * FROM cajas WHERE id = $codcaja");		
		# DATOS fecha caja y responsable
		$cajasf = mysqli_query($conexion, "SELECT fecha, responsable FROM cajas WHERE id = $codcaja");
		$result_cajasf = mysqli_fetch_assoc($cajasf);
		# DATOS CAJA USUARIOS
		$caja = mysqli_query($conexion, "SELECT * FROM caja WHERE id = $codcaja");
		$result_caja = mysqli_fetch_assoc($caja);
		$ids = $result_caja['idsucursal'];
		$nc = $result_caja['nombrecaja'];
		# DATOS SUCURSAL
		$sucursal = mysqli_query($conexion, "SELECT * FROM sucursal WHERE idsucursal = $ids");
		$result_sucursal = mysqli_fetch_assoc($sucursal);	
		# DATOS DETALLE DE LA FACTURA	
		/*$detalleventa = mysqli_query($conexion, "SELECT df.nofactura, df.codmedicamento, df.cantidad, df.fechavencimiento,
													    df.precio_venta, m.nombre,
														m.idmedicamento
														FROM detallefacturas df 
														INNER JOIN medicamento m ON df.codmedicamento = m.idmedicamento
														WHERE df.nofactura = $nofactura"); */

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
			$pdf->SetTextColor(32,100,210);
			$pdf->Cell(180,10,utf8_decode(strtoupper("CIERRE CAJA")),0,0,'C');
			$pdf->Ln(20);
		#

		#Datos de la empresa 
			#Imagen Logo
			$pdf->Image('../factura/img/farmacia2.jpg',165,12,35,35,'JPG');

			#Nombre
			$pdf->SetFont('Arial', 'B', 16);		
			$pdf->Cell(60, 3, utf8_decode($resultado['razon_social']), 0, 1, 'L');
			$pdf->Ln(2);

			#NIT
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->SetFont('Arial', 'B', 10);	
			$pdf->Cell(25,9,utf8_decode("NIT: " ),0,0,'L');
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(160,9,utf8_decode($resultado['nit']),0,0,'L');					
			$pdf->Ln(5);
			#DIRECCION
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->SetFont('Arial', 'B', 10);	
			$pdf->Cell(25,9,utf8_decode("Dirección: " ),0,0,'L');
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(160,9,utf8_decode($resultado['direccion']),0,0,'L');					
			$pdf->Ln(5);		
			#TELÉFONO
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->SetFont('Arial', 'B', 10);	
			$pdf->Cell(25,9,utf8_decode("Teléfono: " ),0,0,'L');
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(160,9,utf8_decode($resultado['telefono']),0,0,'L');					
			$pdf->Ln(5);
			#EMAIL
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->SetFont('Arial', 'B', 10);	
			$pdf->Cell(25,9,utf8_decode("Email: " ),0,0,'L');
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(160,9,utf8_decode($resultado['email']),0,0,'L');					
			$pdf->Ln(12);		
		#

		#Datos de la emision
			# SUBTITULO SECCION
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(15, 5, "DATOS EMISIÓN: ", 0, 0, 'L');
			$pdf->Ln(7);
			
			#FECHA
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(45,7,utf8_decode("Fecha de emisión:"),0,0);
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(100,7,utf8_decode($result_cajasf['fecha']),0,0,'L');		
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(35,7,utf8_decode(strtoupper("Nota Caja Nro.")),0,0,'C');
			$pdf->Ln(5);

			#CAJERO Y NRO DE NOTA DE INGRESO
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(45,7,utf8_decode("Usuario: "),0,0,'L');
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(100,7,$_SESSION['nombre'],0,0,'L');
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(35,7,$codcaja,0,0,'C');
			$pdf->Ln(10);			
		#
		
		#Datos del Proveedor
			#SUBTITULO SECCION
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(15, 5, "DATOS SUCURSAL: ", 0, 0, 'L');
			$pdf->Ln(5);	

			#SUCURSAL Y CAJA
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(25,7,utf8_decode("Sucursal:"),0,0);
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(80,7,utf8_decode($result_sucursal['nombre']),0,0,'L');
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(30,7,utf8_decode("Nº DE CAJA: "),0,0,'L');
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(40,7,utf8_decode($result_caja['nombrecaja']),0,0,'L');			
			$pdf->Ln(10);
		#	
		#Datos del Proveedor
			#SUBTITULO SECCION
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(15, 5, "DETALLE: ", 0, 0, 'L');
			$pdf->Ln(7);
			# Tabla Datos Cja
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(23,83,201);
			$pdf->SetDrawColor(23,83,201);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(10,8,utf8_decode("Id."),1,0,'C',true);
			$pdf->Cell(35,8,utf8_decode("Responsable"),1,0,'C',true);			
			$pdf->Cell(20,8,utf8_decode("apertura"),1,0,'C',true);
			$pdf->Cell(20,8,utf8_decode("Ventas"),1,0,'C',true);		
			$pdf->Cell(20,8,utf8_decode("Egresos"),1,0,'C',true);
			$pdf->Cell(20,8,utf8_decode("Total"),1,0,'C',true);
			$pdf->Cell(20,8,utf8_decode("Efectivo"),1,0,'C',true);
			$pdf->Cell(20,8,utf8_decode("Dif."),1,0,'C',true);
			$pdf->Cell(20,8,utf8_decode("Obs."),1,0,'C',true);
			$pdf->Ln(8);
			$pdf->SetTextColor(39,39,51);

			/*----------  Detalles de la tabla  ----------*/
			while ($row = mysqli_fetch_assoc($cajas)) {
				// $nombreCompleto = $row['nombre'];
				$pdf->SetFont('Arial','',8);
				$pdf->SetDrawColor(23,83,201);				
				$pdf->Cell(10,7,$row['id'],'LB',0,'C');
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(35,7,$row['responsable'],'LRB',0,'L');				
				$pdf->Cell(20,7,$row['apertura'],'LRB',0,'C');
				$pdf->Cell(20,7,$row['ventas'],'LRB',0,'C');
				$pdf->Cell(20,7,$row['egresos'],'LRB',0,'C');
				$pdf->Cell(20,7,$row['total'],'LRB',0,'C');
				$pdf->Cell(20,7,$row['efectivo'],'LRB',0,'C');
				$pdf->Cell(20,7,$row['diferencia'],'LRB',0,'C');
				$pdf->Cell(20,7,$row['observaciones'],'LRB',0,'C');
				$pdf->Ln();				
			}
			
			$pdf->Ln(35);	
			/*----------  Fin Detalles de la tabla  ----------*/
			

	$pdf->SetTextColor(39,39,51);

	
	$pdf->SetFont('Arial','B',9);

	
	$pdf->Cell(65,7,utf8_decode(''),'',0,'C');
	$pdf->Cell(15,7,utf8_decode(''),'',0,'C');


	$pdf->Cell(32,18,utf8_decode("FIRMA"),'T',0,'C');
	$pdf->SetFont('Arial','B',10);		
	$pdf->Cell(-32,7,utf8_decode($result_cajasf['responsable']),0,0,'C');
	$pdf->SetFont('Arial','B',9);	
	$pdf->Ln(7);
	
	# totales #
/* 	$pdf->Cell(200,10,utf8_decode($result_cajasf['responsable']),0,0,'C');	
	
	$pdf->Ln(2);
	$pdf->Cell(200,15,utf8_decode("RESPONSABLE"),0,0,'C');		
	$pdf->Ln(12);
	$pdf->SetFont('Arial','',9);
	$pdf->Ln(9); */


	/* # Codigo de barras #
	$pdf->SetFillColor(39,39,51);
	$pdf->SetDrawColor(23,83,201);
	#$pdf->Code128(72,$pdf->GetY(),"COD000001V0001",70,20);
	$pdf->SetXY(12,$pdf->GetY()+21);
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0,5,utf8_decode("COD000001V0001"),0,'C',false); */

	# Nombre del archivo PDF #
	$pdf->Output("I","Reporte_Cierre_Caja_Nro.$codcaja.pdf",true);	
	
	}

?>
