<?php

	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
	
	# Incluyendo CONECCION #
	include "../../conexion.php";
	include "../includes/functions.php";
	if(empty($_REQUEST['idmed']))
	{
		echo "No es posible generar el kardex";
	}else{

		# Optenemos los datos requeridos para el reporte = NOTA DE INGRESO
		$idmedicamento = $_REQUEST['idmed'];
		# FECHA
		$fecha_actual = date('d-m-Y');
		# DATOS DE LA EMPRESA
		$consulta = mysqli_query($conexion, "SELECT * FROM configuracion");
		$resultado = mysqli_fetch_assoc($consulta);
		# DATOS (NOMBRE Y DESCRIPCION DEL MEDICAMENTO)
		$medicamentos = mysqli_query($conexion, "SELECT m.idmedicamento, m.nombre, m.concentracion,
														pa.medicamento AS despactivo,
														f.nombre AS desforma,
														ate.nombre AS desaterapeutica,
														un.abreviatura AS desunidad														
														FROM medicamento m
														INNER JOIN pactivo pa ON m.idpactivo = pa.id
														INNER JOIN forma f ON m.idforma = f.idforma
														INNER JOIN accionterapeutica ate ON m.idacciont = ate.idaccion
														INNER JOIN unidades un ON m.idunidad = un.idunidad														
														WHERE idmedicamento = $idmedicamento");
		$result_medicamento = mysqli_fetch_assoc($medicamentos);
		$descompleto = 	$result_medicamento['despactivo'] . ' ' . $result_medicamento['desforma'] . ' ' . $result_medicamento['concentracion'] .' '. $result_medicamento['desunidad'] ;
		$nombrearchivo = 	$result_medicamento['nombre'] . ' ' . $result_medicamento['desforma'] . ' ' . $result_medicamento['concentracion'] .' '. $result_medicamento['desunidad'] ;
		
		# MOSTRAMOS LA CABECERA
		
		
		#Incluyendo la libreria requerida
		require_once '../factura/fpdf/fpdf.php';

		#Formato del Documento (hoja)
		$pdf = new FPDF('P','mm','Letter');
		$pdf->AddPage();
		$pdf->SetMargins(17, 17, 17,17);
		$pdf->Ln(1);
		#Titulo - KARDEX
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(32,100,210);
		$pdf->Cell(180,1,strtoupper("KARDEX"),0,0,'L');
		$pdf->Ln(1);
		#NOMBRE DEL MEDICAMENTO
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(10,115,6);		
		$pdf->Cell(180,10,$result_medicamento['nombre'],0,0,'L');
	
		$pdf->Ln(5);

		#Descripcion
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(39,39,51);		
		$pdf->Cell(180,10,$descompleto,0,0,'L');
		$pdf->SetDrawColor(73,100,6);
		$pdf->SetLineWidth(0.5);
		$pdf->Line(17,25,200,25);
		$pdf->Ln(8);
		#FECHA
		$pdf->SetFont('Arial','',8);
		$pdf->SetTextColor(39,39,51);
		$pdf->Cell(28,7,"Fecha Reporte:",0,0);
		$pdf->Cell(28,7,$fecha_actual,0,0);
		$pdf->SetTextColor(97,97,97); 
		$pdf->SetMargins(25, 17, 17,17);
		$pdf->Ln(7);

		#Datos de la empresa = Farmacia Bolivar
		#Imagen Logo
		$pdf->Image('../factura/img/farmacia2.jpg',170,4,20,20,'JPG');

		# CONSULTA SI EXISTE STOCK
		$constock = mysqli_query($conexion, "SELECT DISTINCT fechavencimiento
											FROM kardex 											
											WHERE idmedicamento = $idmedicamento ");
		$resstock = mysqli_num_rows($constock);
		if ($resstock > 0) {
		
		# SI EXISTEN DATOS CONTINUAMOS EXTRANDO LA INFORMACION DE LOS MOVIMIENTOS 
			while ($datastock = mysqli_fetch_assoc($constock)) {
            $fechaven = $datastock['fechavencimiento'];
			$fecven = date_format(date_create_from_format('Y-m-d',$fechaven), 'd-n-Y');
				
			# CABECERA 

			$pdf->SetFont('Arial','B',8	);		
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(95,7,$nombrearchivo,0,0);			
			$pdf->Cell(20,7,('Vencimiento :'),0,0);	
			$pdf->SetTextColor(10,115,6);	
			$pdf->Cell(15,7,($fecven),0,0);
			$pdf->SetTextColor(97,97,97);
			$pdf->Ln(6);	

			# Tabla kardex
			$pdf->SetFont('Arial','',7);	
			$pdf->SetFillColor(170,170,170);
			$pdf->SetDrawColor(255,255,255);
			$pdf->SetTextColor(24,28,34);	
			$pdf->Cell(20,5,"Fecha",1,0,'C',true);
			$pdf->Cell(45,5,"T.Movimiento",1,0,'C',true);
			$pdf->Cell(20,5,"No.Doc.",1,0,'C',true);
			$pdf->Cell(30,5,"Ingreso.",1,0,'C',true);
			$pdf->Cell(30,5,"Salida",1,0,'C',true);
			$pdf->Ln(4);
			$pdf->SetTextColor(39,39,51);		

			# DETALLE PARA CADA REGISTRO O FECHA DE VENCIMIENTO DIFERENTE
			$condetalle = mysqli_query($conexion, "SELECT k.idmov, k.fechamovimiento, k.idtipomov, k.nodoc, k.fechavencimiento,
													k.idsucorigen, k.idsucdestino, k.cantidad,
													tm.descripcion AS desmovimiento
													FROM kardex k
													INNER JOIN tipomovimiento tm ON k.idtipomov = tm.idtipomov
													WHERE idmedicamento = $idmedicamento AND fechavencimiento = '$fechaven'
													ORDER BY idmedicamento ASC, 
													fechavencimiento ASC, 
													fechamovimiento ASC ");
			$resdetalle = mysqli_num_rows($condetalle);	
			if ($resdetalle > 0) {
				
				while ($datadetalle = mysqli_fetch_assoc($condetalle)) {						
					$tipo= $datadetalle['idtipomov'];
					
					/*----------  Detalles de la tabla  ----------*/		
						
						$pdf->SetFont('Arial','',7);
						$pdf->SetDrawColor(23,83,201);
						$pdf->Cell(20,6,($datadetalle['fechamovimiento']),0,0,'C');			
						$pdf->Cell(45,6,$datadetalle['desmovimiento'],0,0,'L');
						$pdf->Cell(20,6,$datadetalle['nodoc'],0,0,'C');
						if (($tipo == 1) || ($tipo == 3) || ($tipo == 5)) {
							$ingreso = $datadetalle['cantidad'];
							$salida = 0;						
						} else {
							$ingreso = 0;
							$salida = $datadetalle['cantidad'];													
						}													
						$pdf->Cell(30,6,$ingreso,0,0,'C');			
						$pdf->Cell(30,6,$salida,0,0,'C');						
						$pdf->Ln(4);	 	
				
					
					/*----------  Fin Detalles de la tabla  ----------*/	
		
					}
					$pdf->Ln(1);
					# RESUMEN 
					$querying = mysqli_query($conexion, "SELECT SUM(cantidad) AS ingresos FROM kardex 
															WHERE (idtipomov = 1 OR idtipomov = 3 OR idtipomov= 5) 
															AND idmedicamento = $idmedicamento
															AND fechavencimiento = '$fechaven'");
					$dataing =mysqli_fetch_assoc($querying);
					$ing = $dataing['ingresos'];
					$queryeg = mysqli_query($conexion, "SELECT SUM(cantidad) AS egresos FROM kardex 
															WHERE (idtipomov = 2 OR idtipomov = 4 OR idtipomov = 6 OR idtipomov = 7) 
															AND idmedicamento = $idmedicamento
															AND fechavencimiento = '$fechaven'");
					$dataeg =mysqli_fetch_assoc($queryeg);
					$eg = $dataeg['egresos'];
					$total = $ing - $eg;
					$pdf->SetFont('Arial','B',8);		
					$pdf->SetTextColor(32,100,210);
					$pdf->Cell(97,5,'',0,0);
					$pdf->Cell(30,5,($ing),'TB',0);
					$pdf->SetTextColor(85,0,0);
					$pdf->Cell(20,5,($eg),'TB',0);
					$pdf->SetTextColor(4,91,98);
					$pdf->Cell(12,5,($total),'TB',0);
					$pdf->SetTextColor(97,97,97);					
					$pdf->Ln(8);
			} else {
				# code...
			}
					
			

			}
		
		} else {
			# MOSTRAMOS EL MENSAJE DE QUE NO EXISTE MOVIMIENTO NI STOCK DEL MEDICAMENTO 		
			$pdf->SetFont('Arial','',10);
		
			$pdf->SetFillColor(4,91,98);
			$pdf->SetDrawColor(255,255,255);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(150,8,"NO SE REGISTRA MOVIMIENTO DE INGRESO  SALIDA DEL MEDICAMENTO",1,0,'C',true);			
			$pdf->Ln(8);
			$pdf->SetTextColor(39,39,51);
		}
			
	
		
		$pdf->SetDrawColor(23,83,201);
		
		# Nombre del archivo PDF #
		$pdf->Output("I","Kardex_.$nombrearchivo.pdf",true);	
		
		}

?>
