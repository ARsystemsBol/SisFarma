<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM cliente 
                                            WHERE (idcliente = $id) 
                                            AND (idcliente != 1)");
    mysqli_close($conexion);
    header("location: lista_cliente.php");
}
?>