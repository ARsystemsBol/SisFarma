<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $codunidad = $_GET['id'];
    $query_consulta = mysqli_query($conexion, "SELECT idmedicamento FROM medicamento WHERE idunidad = $codunidad");
    $result = mysqli_fetch_array($query_consulta);
    if ($result > 0) {       
        echo '<script language="javascript">alert("No se puede Eliminar");window.location.href="registro_unidades.php"</script>';      
    } else {         
        $query_delete = mysqli_query($conexion, "DELETE FROM unidades WHERE idunidad = $codunidad");
        echo '<script language="javascript">alert("Registro Eliminado");window.location.href="registro_unidades.php"</script>';  
   }
    mysqli_close($conexion);  
}
?>