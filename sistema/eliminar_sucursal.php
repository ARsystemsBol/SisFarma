<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $idsucursal = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM sucursal 
                                            WHERE (idsucursal = $idsucursal) 
                                            AND (almacen  != 1)");
    mysqli_close($conexion);
    header("location: lista_sucursales.php");
}
?>