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
		$medicamento = mysqli_query($conexion, "SELECT m.idmedicamento, m.codmedicamento, m.nombre, m.concentracion, m.stock,
											pa.medicamento AS despactivo,
											f.nombre AS desforma,
											ate.nombre AS desaterapeutica,
											un.abreviatura AS desunidad,
											u.descripcion AS desuso 
											FROM medicamento m
											INNER JOIN pactivo pa ON m.idpactivo = pa.id
											INNER JOIN forma f ON m.idforma = f.idforma
											INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
											INNER JOIN unidades un ON m.idunidad = un.idunidad
											INNER JOIN uso u ON pa.uso = u.id
											ORDER BY m.nombre ASC");		
		
		#Incluyendo la libreria requerida
		require_once '../factura/fpdf/fpdf.php';

		#Formato del Documento (hoja)
		$pdf = new FPDF('L','mm','Letter');	
		$pdf->AddPage();
		$pdf->SetMargins(17, 17, 17,17);
		$pdf->Ln(9);
		#Titulo - KARDEX
		$pdf->SetFont('Arial','B',14);
		$pdf->SetTextColor(32,100,210);
		$pdf->Cell(180,1,utf8_decode(strtoupper("REPORTE GENERAL DE MEDICAMENTOS")),0,0,'L');
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
		$pdf->Cell(28,7,utf8_decode("Fecha Reporte:"),0,0);
		$pdf->SetTextColor(97,97,97);
		
		
		$pdf->Ln(10);

		#Datos de la empresa = Farmacia Bolivar
		#Imagen Logo
		$pdf->Image('../factura/img/farmacia2.jpg',240,10,20,20,'JPG');

		

		# Tabla Medicamentos
		$pdf->SetFont('Arial','',10);
		$pdf->SetFillColor(23,83,201);
		$pdf->SetDrawColor(255,255,255);
		$pdf->SetTextColor(255,255,255);
		$pdf->Cell(5,8,utf8_decode("id"),1,0,'C',true);
		$pdf->Cell(80,8,utf8_decode("Nombre"),1,0,'C',true);		
		$pdf->Cell(50,8,utf8_decode("Forma."),1,0,'C',true);
		$pdf->Cell(30,8,utf8_decode("Con."),1,0,'C',true);		
		$pdf->Cell(75,8,utf8_decode("A. Terapeutica"),1,0,'C',true);		
		$pdf->Ln(8);
		$pdf->SetTextColor(39,39,51);

		/*----------  Detalles de la tabla  ----------*/
		while ($row = mysqli_fetch_assoc($medicamento)) {
			$row['nombrecompleto'] = $row['nombre'].' ' . $row['desforma'].' ' . $row['concentracion'].' ' . $row['desunidad']. ' ' . $row['desaterapeutica'];
			$row['conc'] = $row['concentracion'].' ' . $row['desunidad'];
			$pdf->SetFont('Arial','',8);
			$pdf->SetDrawColor(23,83,201);
			$pdf->Cell(5,10,($row['idmedicamento']),'LB',0,'C');			
			$pdf->Cell(80,10,utf8_decode($row['nombre']),'LB',0,'L');
			$pdf->Cell(50,10,utf8_decode($row['desforma']),'LB',0,'L');			
			$pdf->Cell(30,10,utf8_decode($row['conc']),'LRB',0,'L');
			$pdf->Cell(75,10,utf8_decode($row['desaterapeutica']),'LRB',0,'L');		
			$pdf->Ln();				
		}
		$pdf->Ln(5);	
		/*----------  Fin Detalles de la tabla  ----------*/
		
	
		
		
		
		
		$pdf->SetDrawColor(23,83,201);
		
		# Nombre del archivo PDF #
		$pdf->Output("I","Nota_Ingreso_Lote_Nro.2.pdf",true);	
	
		

?>
