<?php
session_name('control_sesion');
session_start();
include '../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $edad = filter_input(INPUT_POST, 'edad', FILTER_SANITIZE_STRING);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $miniatura = filter_input(INPUT_POST, 'miniatura', FILTER_SANITIZE_URL);
    $idAdmin = $_SESSION["idcontrol"];
    $disponible = 1;

    if (!$nombre || !$descripcion || !$miniatura || !$edad) {
        echo "Entrada Invalida";
        exit;
    }

    $nombre = mysqli_real_escape_string($conection, $nombre);
    $descripcion = mysqli_real_escape_string($conection, $descripcion);
    $miniatura = mysqli_real_escape_string($conection, $miniatura);
    $edad = mysqli_real_escape_string($conection, $edad);

    $sql = "INSERT INTO perro (nombre, edad,descripcion, img, disponible, albergue_id) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conection, $sql)) {
        mysqli_stmt_bind_param($stmt, 'sissii', $nombre, $edad, $descripcion, $miniatura, $disponible, $idAdmin);
        if (mysqli_stmt_execute($stmt)) {
            echo "success";
        } else {
            echo "error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "error: " . mysqli_error($conection);
    }
}

mysqli_close($conection);
?>

