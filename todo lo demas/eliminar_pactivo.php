<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $codpactivo = $_GET['id'];
    $query_consulta = mysqli_query($conexion, "SELECT idmedicamento FROM medicamento WHERE idpactivo = $codpactivo");
    $result = mysqli_fetch_array($query_consulta);
    if ($result > 0) {       
        echo '<script language="javascript">alert("No se puede Eliminar");window.location.href="registro_pactivo.php"</script>';      
    } else {         
        $query_delete = mysqli_query($conexion, "DELETE FROM pactivo WHERE id = $codpactivo");
        echo '<script language="javascript">alert("Registro Eliminado");window.location.href="registro_pactivo.php"</script>';  
   }
    mysqli_close($conexion);  
}
?>