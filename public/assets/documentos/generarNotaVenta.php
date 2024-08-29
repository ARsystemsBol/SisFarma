<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
	# Incluyendo CONECCION #
	include "../../conexion.php";
	if(empty($_REQUEST['cl']) || empty($_REQUEST['f']))
	{
		echo "No es posible generar la nota de venta";
	}else{

		#Optenemos los datos requeridos para el reporte =
			
		$codcliente = $_REQUEST['cl'];
		$nofactura = $_REQUEST['f'];
		# DATOS DE LA EMPRESA	
		$consulta = mysqli_query($conexion, "SELECT * FROM configuracion");
		$resultado = mysqli_fetch_assoc($consulta);
		# DATOS DE LA FACTURA
		$factura = mysqli_query($conexion, "SELECT * FROM facturas WHERE nofactura = $nofactura");
		$result_factura = mysqli_fetch_assoc($factura);
		# DATOS DEL CLIENTE
		$cliente = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $codcliente");
		$result_cliente = mysqli_fetch_assoc($cliente);	
		# DATOS DETALLE DE LA FACTURA	
		$detalleventa = mysqli_query($conexion, "SELECT df.nofactura, df.codmedicamento, df.cantidad, df.fechavencimiento,
													    df.precio_venta, m.nombre,
														m.idmedicamento
														FROM detallefacturas df 
														INNER JOIN medicamento m ON df.codmedicamento = m.idmedicamento
														WHERE df.nofactura = $nofactura");

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
			$pdf->Cell(180,10,utf8_decode(strtoupper("NOTA DE VENTA")),0,0,'C');
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
			$pdf->Cell(15, 5, "DATOS GENERALES: ", 0, 0, 'L');
			$pdf->Ln(7);
			
			#FECHA
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(45,7,utf8_decode("Fecha de emisión:"),0,0);
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(100,7,utf8_decode($result_factura['fecha']),0,0,'L');		
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(35,7,utf8_decode(strtoupper("Nota Venta Nro.")),0,0,'C');
			$pdf->Ln(5);

			#CAJERO Y NRO DE NOTA DE INGRESO
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(45,7,utf8_decode("Usuario: "),0,0,'L');
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(100,7,$_SESSION['nombre'],0,0,'L');
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(35,7,$nofactura,0,0,'C');
			$pdf->Ln(10);			
		#
		
		#Datos del Proveedor
			#SUBTITULO SECCION
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(15, 5, "DATOS CLIENTE: ", 0, 0, 'L');
			$pdf->Ln(5);	

			#NOMBRE - NIT 
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(25,7,utf8_decode("Cliente:"),0,0);
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(110,7,utf8_decode($result_cliente['nombre']),0,0,'L');
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(10,7,utf8_decode("NIT: "),0,0,'L');
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(40,7,$result_cliente['nit_ci'],0,0,'L');			
			$pdf->Ln(10);
		#	
			
		#Datos del Proveedor
			#SUBTITULO SECCION
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(15, 5, "DETALLE: ", 0, 0, 'L');
			$pdf->Ln(7);
			# Tabla Medicamentos
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(23,83,201);
			$pdf->SetDrawColor(255,255,255);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(90,8,utf8_decode("Descripción"),1,0,'C',true);
			$pdf->Cell(20,8,utf8_decode("Cant."),1,0,'C',true);
			$pdf->Cell(20,8,utf8_decode("Fec. Ven."),1,0,'C',true);
			$pdf->Cell(20,8,utf8_decode("Precio /u."),1,0,'C',true);		
			$pdf->Cell(28,8,utf8_decode("Subtotal"),1,0,'C',true);
			$pdf->Ln(8);
			$pdf->SetTextColor(39,39,51);

			/*----------  Detalles de la tabla  ----------*/
			while ($row = mysqli_fetch_assoc($detalleventa)) {
				// $nombreCompleto = $row['nombre'];
				$pdf->SetFont('Arial','',8);
				$pdf->SetDrawColor(23,83,201);
				$pdf->Cell(90,7,$row['nombre'],'LB',0,'l');
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(20,7,$row['cantidad'],'LB',0,'C');
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(20,7,$row['fechavencimiento'],'LB',0,'C');
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(20,7,$row['precio_venta'],'LB',0,'C');			
				$pdf->Cell(28,7,$row['cantidad'] * $row['precio_venta'],'LRB',0,'C');
				$pdf->Ln();				
			}
			$pdf->Ln(5);	
			/*----------  Fin Detalles de la tabla  ----------*/
			

	


	
	$pdf->SetTextColor(39,39,51);

	
	$pdf->SetFont('Arial','B',9);
	
	# totales #
	

	$pdf->Cell(100,7,utf8_decode(''),'',0,'C');
	$pdf->Cell(15,7,utf8_decode(''),'',0,'C');


	$pdf->Cell(32,9,utf8_decode("TOTAL A PAGAR"),'T',0,'C');
	$pdf->Cell(34,9,$result_factura['totalfactura'],'T',0,'C');
	
	$pdf->Ln(12);

	$pdf->SetFont('Arial','',9);

	$pdf->Ln(9);

	/* # Codigo de barras #
	$pdf->SetFillColor(39,39,51);
	$pdf->SetDrawColor(23,83,201);
	#$pdf->Code128(72,$pdf->GetY(),"COD000001V0001",70,20);
	$pdf->SetXY(12,$pdf->GetY()+21);
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0,5,utf8_decode("COD000001V0001"),0,'C',false); */

	# Nombre del archivo PDF #
	$pdf->Output("I","Nota_Venta_Nro.$nofactura.pdf",true);	
	
	}

?>
