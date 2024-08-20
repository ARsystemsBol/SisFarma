<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $idcaja = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM caja 
                                            WHERE (idsucursal = $idcaja) 
                                            AND (id  != 1)");
    mysqli_close($conexion);
    header("location: registro_caja.php");
}
?>