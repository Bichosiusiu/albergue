<?php
session_name('control_sesion');
session_start();
include '../../includes/conexion.php';

if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_update_orders = "UPDATE adopcion SET estado = 'Adoptado' WHERE id = ?";
    if ($stmt_orders = mysqli_prepare($conection, $sql_update_orders)) {
        mysqli_stmt_bind_param($stmt_orders, 'i', $id);
        mysqli_stmt_execute($stmt_orders);
        mysqli_stmt_close($stmt_orders);
    }
    echo "success";
} else {
    echo "error: Parametros invalidos";
}
mysqli_close($conection);