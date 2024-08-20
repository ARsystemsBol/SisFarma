<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $codaccion = $_GET['id'];
    $query_consulta = mysqli_query($conexion, "SELECT idmedicamento FROM medicamento WHERE idacciont= $codaccion");
    $result = mysqli_fetch_array($query_consulta);
    if ($result > 0) {       
        echo '<script language="javascript">alert("No se puede Eliminar");window.location.href="registro_accionterapeutica.php"</script>';      
    } else {         
        $query_delete = mysqli_query($conexion, "DELETE FROM accionterapeutica WHERE idaccion = $codaccion");
        echo '<script language="javascript">alert("Registro Eliminado");window.location.href="registro_accionterapeutica.php"</script>';  
   }
    mysqli_close($conexion);  
}
?>