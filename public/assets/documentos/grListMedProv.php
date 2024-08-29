<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
	# Incluyendo CONECCION #
	include "../../conexion.php";
	include "../includes/functions.php";	
	if(empty($_REQUEST['l']))
	{
		echo "No es posible generar el reporte";
	}else{	
		#Optenemos los datos requeridos para el reporte = NOTA DE INGRESO
		$lab = $_REQUEST['l'];		
		$consulta = mysqli_query($conexion, "SELECT * FROM configuracion");
		$resultado = mysqli_fetch_assoc($consulta);
		$proveedor = mysqli_query($conexion, "SELECT * FROM proveedor WHERE codproveedor = $lab");	
		$resultado_p = mysqli_fetch_assoc($proveedor);
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
											WHERE m.laboratorio = $lab
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
		$pdf->Cell(180,1,strtoupper("REPORTE MEDICAMENTOS x LABORATORIO"),0,0,'L');
		$pdf->Ln(3);
		#NOMBRE DEL PROVEEDOR
		$pdf->SetFont('Arial','B',16);
		$pdf->SetTextColor(73,115,6);		
		$pdf->Cell(180,10,$resultado_p['proveedor'],0,0,'L');
		
		
		
		$pdf->Ln(10);
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

		

		# Tabla Medicamentos
		$pdf->SetFont('Arial','',10);
		$pdf->SetFillColor(23,83,201);
		$pdf->SetDrawColor(255,255,255);
		$pdf->SetTextColor(255,255,255);
		$pdf->Cell(5,8,"id",1,0,'C',true);
		$pdf->Cell(80,8,"Nombre",1,0,'C',true);		
		$pdf->Cell(50,8,"Forma.",1,0,'C',true);
		$pdf->Cell(30,8,"Con.",1,0,'C',true);		
		$pdf->Cell(75,8,"A. Terapeutica",1,0,'C',true);		
		$pdf->Ln(8);
		$pdf->SetTextColor(39,39,51);

		/*----------  Detalles de la tabla  ----------*/
		while ($row = mysqli_fetch_assoc($medicamento)) {
			$row['nombrecompleto'] = $row['nombre'].' ' . $row['desforma'].' ' . $row['concentracion'].' ' . $row['desunidad']. ' ' . $row['desaterapeutica'];
			$row['conc'] = $row['concentracion'].' ' . $row['desunidad'];
			$pdf->SetFont('Arial','',8);
			$pdf->SetDrawColor(23,83,201);
			$pdf->Cell(5,10,($row['idmedicamento']),'LB',0,'C');			
			$pdf->Cell(80,10,$row['nombre'],'LB',0,'L');
			$pdf->Cell(50,10,$row['desforma'],'LB',0,'L');			
			$pdf->Cell(30,10,$row['conc'],'LRB',0,'L');
			$pdf->Cell(75,10,$row['desaterapeutica'],'LRB',0,'L');		
			$pdf->Ln();				
		}
		$pdf->Ln(5);	
		/*----------  Fin Detalles de la tabla  ----------*/
		
	
		
		
		
		
		$pdf->SetDrawColor(23,83,201);
		
		# Nombre del archivo PDF #
		$pdf->Output("I","Nota_Ingreso_Lote_Nro.2.pdf",true);	
		
		
	}
?>
