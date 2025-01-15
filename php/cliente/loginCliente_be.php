<?php
session_name('cliente_sesion');
session_start();
include "../../includes/conexion.php";
$response = array(); 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = sanitizeInput($_POST["correo"], $conection);
    $password = sanitizeInput($_POST["password"], $conection);
    if (empty($correo) || empty($password)) {
        $response['success'] = false;
        $response['message'] = "Todos los campos son obligatorios.";
        echo json_encode($response);
        exit();
    }
    $query = "SELECT * FROM adoptante WHERE email='$correo' AND contraseña='$password'";
    $validar = mysqli_query($conection, $query);
    if (mysqli_num_rows($validar) > 0) {
        $usuario = mysqli_fetch_assoc($validar);
        $_SESSION["idCliente"] = $usuario['id'];
        $_SESSION["nomcompleto"] = $usuario['nombre'];
        $_SESSION["email"] = $usuario['email'];
        $response['success'] = true;
        $response['redirect'] = "MainCliente.php";
    } else {
        $response['success'] = false;
        $response['message'] = "Datos Erroneos. Favor de Ingresar Datos Correctos";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Solicitud no válida";
}

echo json_encode($response);
?>