<?php
// Asegúrate de que la extensión esté cargada correctamente y de que la conexión esté configurada
$serverName = "SERVIDORAKSQL";
$database = "INTIMARKDBAXPRODLIVE";
$username = "sa";
$password = "IntimArk";

$conn = sqlsrv_connect($serverName, array("Database"=>$database, "UID"=>$username, "PWD"=>$password));

if($conn) {
    echo "Conexión establecida.";
} else {
    echo "Error en la conexión.";
    die(print_r(sqlsrv_errors(), true));
}
?>
