<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
	# Incluyendo CONECCION #
	include "../../conexion.php";
	if(empty($_REQUEST['p']) || empty($_REQUEST['l']))
	{
		echo "No es posible generar la nota de ingreso.";
	}else{

		#Optenemos los datos requeridos para el reporte = NOTA DE INGRESO
		$codproveedor = $_REQUEST['p'];
		$nolote = $_REQUEST['l'];	
		$consulta = mysqli_query($conexion, "SELECT * FROM configuracion");
		$resultado = mysqli_fetch_assoc($consulta);
		$lotes = mysqli_query($conexion, "SELECT * FROM lote WHERE nolote = $nolote");
		$result_lote = mysqli_fetch_assoc($lotes);
		$proveedores = mysqli_query($conexion, "SELECT * FROM proveedor WHERE codproveedor = $codproveedor");
		$result_proveedor = mysqli_fetch_assoc($proveedores);		
		$medicamentos = mysqli_query($conexion, "SELECT d.nolote, d.codmedicamento, d.cantidad, d.fecvencimiento, 
														m.idmedicamento, m.nombre, m.preciounitario
														FROM detallelote d 
														INNER JOIN medicamento m ON d.nolote = $nolote 
														WHERE d.codmedicamento = m.idmedicamento");
	
		
		#Incluyendo la libreria requerida
		require_once '../factura/fpdf/fpdf.php';

		#Formato del Documento (hoja)
		$pdf = new FPDF('P','mm','Letter');
		$pdf->AddPage();
		$pdf->SetMargins(17, 17, 17);
		$pdf->Ln(9);
		#Titulo
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(32,100,210);
		$pdf->Cell(180,10,strtoupper("NOTA DE INGRESO"),0,0,'C');
		$pdf->Ln(20);

		#Datos de la empresa 
		#Imagen Logo
		$pdf->Image('../factura/img/farmacia2.jpg',165,12,35,35,'JPG');

		#Nombre
		$pdf->SetFont('Arial', 'B', 16);		
		$pdf->Cell(60, 3, $resultado['razon_social'], 0, 1, 'L');
		$pdf->Ln(2);

		#NIT
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(39,39,51);
		$pdf->SetFont('Arial', 'B', 10);	
		$pdf->Cell(25,9,"NIT: " ,0,0,'L');
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(160,9,$resultado['nit'],0,0,'L');					
		$pdf->Ln(5);
		#DIRECCION
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(39,39,51);
		$pdf->SetFont('Arial', 'B', 10);	
		$pdf->Cell(25,9,"Dirección: " ,0,0,'L');
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(160,9,$resultado['direccion'],0,0,'L');					
		$pdf->Ln(5);		
		#TELÉFONO
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(39,39,51);
		$pdf->SetFont('Arial', 'B', 10);	
		$pdf->Cell(25,9,"Teléfono: " ,0,0,'L');
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(160,9,$resultado['telefono'],0,0,'L');					
		$pdf->Ln(5);
		#EMAIL
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(39,39,51);
		$pdf->SetFont('Arial', 'B', 10);	
		$pdf->Cell(25,9,"Email: " ,0,0,'L');
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(160,9,$resultado['email'],0,0,'L');					
		$pdf->Ln(12);		

		#Datos de la emision
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(15, 5, "DETALLE INGRESO DE LOTE: ", 0, 0, 'L');
		$pdf->Ln(7);		
		#FECHA
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(39,39,51);
		$pdf->Cell(45,7,"Fecha de emisión:",0,0);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(100,7,$result_lote['fecha'],0,0,'L');		
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(39,39,51);
		$pdf->Cell(35,7,strtoupper("Nota Ingreso Nro."),0,0,'C');
		$pdf->Ln(7);
		#CAJERO Y NRO DE NOTA DE INGRESO
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(45,7,"Usuario: ",0,0,'L');
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(100,7,$_SESSION['nombre'],0,0,'L');
		$pdf->SetFont('Arial','B',14);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(35,7,$nolote,0,0,'C');
		$pdf->Ln(7);
		#DESCRIPCION 
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(39,39,51);
		$pdf->Cell(45,7,"Descripción: ",0,0,'L');
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(100,7,$result_lote['descripcion'],0,0,'L');		
		$pdf->Ln(10);

		#Datos del Proveedor
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(15, 5, "DATOS DEL PROVEEDOR: ", 0, 0, 'L');
		$pdf->Ln(7);		
		#NOMBRE - NIT 
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(39,39,51);
		$pdf->Cell(25,7,"Proveedor:",0,0);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(110,7,$result_proveedor['proveedor'],0,0,'L');
		$pdf->SetTextColor(39,39,51);
		$pdf->Cell(10,7,"NIT: ",0,0,'L');
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(40,7,$result_proveedor['nit'],0,0,'L');
		$pdf->SetTextColor(39,39,51);
		$pdf->Ln(7);
		#DIRECCION - TELEFONO
		$pdf->SetTextColor(39,39,51);
		$pdf->Cell(25,7,"Dirección:",0,0);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(110,7,$result_proveedor['direccion'],0,0);				
		$pdf->Cell(10,7,"Tel:",0,0,'L');
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(35,7,$result_proveedor['telefono'],0,0);
		$pdf->SetTextColor(39,39,51);
		$pdf->Ln(7);		
		#Datos del Proveedor
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(15, 5, "DETALLE DE MEDICAMENTOS: ", 0, 0, 'L');
		$pdf->Ln(7);

		# Tabla Medicamentos
		$pdf->SetFont('Arial','',10);
		$pdf->SetFillColor(23,83,201);
		$pdf->SetDrawColor(255,255,255);
		$pdf->SetTextColor(255,255,255);
		$pdf->Cell(90,8,"Descripción",1,0,'C',true);
		$pdf->Cell(20,8,"Cant.",1,0,'C',true);
		$pdf->Cell(20,8,"Fec. Ven.",1,0,'C',true);
		$pdf->Cell(20,8,"Precio /u.",1,0,'C',true);		
		$pdf->Cell(28,8,"Subtotal",1,0,'C',true);
		$pdf->Ln(8);
		$pdf->SetTextColor(39,39,51);

		/*----------  Detalles de la tabla  ----------*/
		while ($row = mysqli_fetch_assoc($medicamentos)) {
			$nombreCompleto = $row['nombre'];
			$pdf->SetFont('Arial','',8);
			$pdf->SetDrawColor(23,83,201);
			$pdf->Cell(90,7,($nombreCompleto),'LB',0,'l');
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(20,7,$row['cantidad'],'LB',0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(20,7,$row['fecvencimiento'],'LB',0,'C');
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(20,7,$row['preciounitario'],'LB',0,'C');			
			$pdf->Cell(28,7,$row['cantidad'] * $row['preciounitario'],'LRB',0,'C');
			$pdf->Ln();				
		}
		$pdf->Ln(5);	
		/*----------  Fin Detalles de la tabla  ----------*/
		
		$pdf->SetFont('Arial','B',9);
		
		# Impuestos & totales #
		#$pdf->Cell(100,7,utf8_decode(''),'T',0,'C');
		#$pdf->Cell(15,7,utf8_decode(''),'T',0,'C');
		#$pdf->Cell(32,7,utf8_decode("SUBTOTAL"),'T',0,'C');
		#$pdf->Cell(34,7,utf8_decode("+ $70.00 USD"),'T',0,'C');

		#$pdf->Ln(7);

		#$pdf->Cell(100,7,utf8_decode(''),'',0,'C');
		#$pdf->Cell(15,7,utf8_decode(''),'',0,'C');
		#$pdf->Cell(32,7,utf8_decode("IVA (13%)"),'',0,'C');
		#$pdf->Cell(34,7,utf8_decode("+ $0.00 USD"),'',0,'C');

		#$pdf->Ln(7);

		$pdf->Cell(100,7,'','',0,'C');
		$pdf->Cell(15,7,'','',0,'C');


		$pdf->Cell(32,7,"TOTAL A PAGAR",'T',0,'C');
		$pdf->SetFont('Arial','B',12);		
		$pdf->Cell(34,7,number_format($result_lote['totalcompra']),'T',0,'C');
		$pdf->SetFont('Arial','B',9);	
		$pdf->Ln(7);

		#$pdf->Cell(100,7,utf8_decode(''),'',0,'C');
		#$pdf->Cell(15,7,utf8_decode(''),'',0,'C');
		#$pdf->Cell(32,7,utf8_decode("TOTAL PAGADO"),'',0,'C');
		#$pdf->Cell(34,7,utf8_decode("$100.00 USD"),'',0,'C');

		#$pdf->Ln(7);

		#$pdf->Cell(100,7,utf8_decode(''),'',0,'C');
		#$pdf->Cell(15,7,utf8_decode(''),'',0,'C');
		#$pdf->Cell(32,7,utf8_decode("CAMBIO"),'',0,'C');
		#$pdf->Cell(34,7,utf8_decode("$30.00 USD"),'',0,'C');

		#$pdf->Ln(7);

		#$pdf->Cell(100,7,utf8_decode(''),'',0,'C');
		#$pdf->Cell(15,7,utf8_decode(''),'',0,'C');
		#$pdf->Cell(32,7,utf8_decode("USTED AHORRA"),'',0,'C');
		#$pdf->Cell(34,7,utf8_decode("$0.00 USD"),'',0,'C');

		#$pdf->Ln(12);

		#$pdf->SetFont('Arial','',9);

		#$pdf->SetTextColor(39,39,51);
		#$pdf->MultiCell(0,9,utf8_decode("*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar esta factura ***"),0,'C',false);

		#$pdf->Ln(9);

		# Codigo de barras #
		#$pdf->SetFillColor(39,39,51);
		$pdf->SetDrawColor(23,83,201);
		#$pdf->Code128(72,$pdf->GetY(),"COD000001V0001",70,20);
		#$pdf->SetXY(12,$pdf->GetY()+21);
		#$pdf->SetFont('Arial','',12);
		#$pdf->MultiCell(0,5,utf8_decode("COD000001V0001"),0,'C',false);

		# Nombre del archivo PDF #
		$pdf->Output("I","Nota_Ingreso_Lote_Nro.$nolote.pdf",true);	
		
		}

?>
