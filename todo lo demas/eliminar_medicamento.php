<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $codmedicamento = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM medicamento WHERE idmedicamento = $codmedicamento");
    mysqli_close($conexion);
    header("location: lista_medicamentos.php");
}
?>