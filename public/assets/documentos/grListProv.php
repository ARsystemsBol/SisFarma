<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
	# Incluyendo CONECCION #
	include "../../conexion.php";
	include "../includes/functions.php";	

		#Optenemos los datos requeridos para el reporte = NOTA DE INGRESO
				
		$consulta = mysqli_query($conexion, "SELECT * FROM configuracion");
		$resultado = mysqli_fetch_assoc($consulta);
		$proveedor = mysqli_query($conexion, "SELECT * FROM proveedor");		
		
		#Incluyendo la libreria requerida
		require_once '../factura/fpdf/fpdf.php';

		#Formato del Documento (hoja)
		$pdf = new FPDF('L','mm','Letter');	
		$pdf->AddPage();
		$pdf->SetMargins(17, 17, 17,17);
		$pdf->Ln(9);
		#Titulo - KARDEX
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(32,100,210);
		$pdf->Cell(180,1,strtoupper("REPORTE PROVEEDORES"),0,0,'L');
		$pdf->Ln(3);
		#NOMBRE DEL MEDICAMENTO
		#$pdf->SetFont('Arial','B',18);
		#$pdf->SetTextColor(73,115,6);		
		#$pdf->Cell(180,10,utf8_decode($result_proveedor['proveedor']),0,0,'L');
		#$pdf->SetDrawColor(73,115,6);
		#$pdf->SetLineWidth(0.5);
		#$pdf->Line(18,41,195,41);
		#$pdf->Ln(6);
		#
		#FECHA
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(39,39,51);
		$pdf->Cell(28,7,"Fecha Reporte:",0,0);
		$pdf->SetTextColor(97,97,97);
		
		
		$pdf->Ln(10);

		#Datos de la empresa = Farmacia Bolivar
		#Imagen Logo
		$pdf->Image('../factura/img/farmacia2.jpg',240,10,20,20,'JPG');

		

		# Tabla kardex
		$pdf->SetFont('Arial','',10);
		$pdf->SetFillColor(23,83,201);
		$pdf->SetDrawColor(255,255,255);
		$pdf->SetTextColor(255,255,255);
		$pdf->Cell(5,8,"id",1,0,'C',true);
		$pdf->Cell(100,8,"Nombre",1,0,'C',true);		
		$pdf->Cell(15,8,"Telf.",1,0,'C',true);
		$pdf->Cell(50,8,"mail",1,0,'C',true);		
		$pdf->Cell(75,8,"direccion",1,0,'C',true);
		$pdf->Ln(8);
		$pdf->SetTextColor(39,39,51);

		/*----------  Detalles de la tabla  ----------*/
		while ($row = mysqli_fetch_assoc($proveedor)) {
			$pdf->SetFont('Arial','',8);
			$pdf->SetDrawColor(23,83,201);
			$pdf->Cell(5,10,($row['codproveedor']),'LB',0,'C');			
			$pdf->Cell(100,10,$row['proveedor'],'LB',0,'L');
			$pdf->Cell(15,10,$row['telefono'],'LB',0,'C');			
			$pdf->Cell(50,10,$row['mail'],'LRB',0,'L');
			$pdf->Cell(75,10,$row['direccion'],'LRB',0,'L');
			$pdf->Ln();				
		}
		$pdf->Ln(5);	
		/*----------  Fin Detalles de la tabla  ----------*/
		
	
		
		
		
		
		$pdf->SetDrawColor(23,83,201);
		
		# Nombre del archivo PDF #
		$pdf->Output("I","Nota_Ingreso_Lote_Nro.2.pdf",true);	
		
		

?>
