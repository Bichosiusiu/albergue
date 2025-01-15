<?php
include '../../includes/conexion.php';
function validarContraseña($password) {
    if (strlen($password) < 8) {
        return false;
    }
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z0-9]/', $password)) {
        return false;
    }
    return true;
}
$nombre = sanitizeInput($_POST["nom"], $conection);
$apellido = sanitizeInput($_POST["ap"], $conection);
$dir = sanitizeInput($_POST["dir"], $conection);
$correo = sanitizeInput($_POST["correo"], $conection);
$password = sanitizeInput($_POST["password"], $conection);

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo "Correo no válido.";
    exit();
}

if (!validarContraseña($password)) {
    echo "La contraseña debe contener al menos 8 caracteres, una mayúscula, un número y un caracter especial.";
    exit();
}
$verificar = mysqli_query($conection, "SELECT * FROM adoptante WHERE email='$correo'");
if (mysqli_num_rows($verificar) > 0) {
    echo "Correo ya existente";
    exit();
}
$query = "INSERT INTO adoptante(nombre, telefono, email, direccion, contraseña) VALUES ('$nombre', '$apellido', '$correo','$dir', '$password')";
$ejecutar = mysqli_query($conection, $query);

if ($ejecutar) {
    echo "exito";
} else {
    echo "fracaso";
}

mysqli_close($conection);
?>
