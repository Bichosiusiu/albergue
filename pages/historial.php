<?php 
session_name('cliente_sesion');
session_start();
include "../includes/conexion.php";
if(!isset($_SESSION["idCliente"])){
    echo "
    <script>
    alert('Por favor, debes iniciar sesión');
    window.location='index.html';
    </script>";
    session_destroy();
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/estilos.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <title>Historial de Adopcion</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header>
    <div class="menu-toggle">
        <i class="material-icons" id="menu-icon" style="display: block">menu</i>
        </div>
        <div class="navegador" id="menu">
            <a href="index.html">
                <div class="opcion">
                    <i class="material-icons">home</i>
                    <span>Inicio</span>
                </div>
            </a>
            <a href="MainCliente.php">
                <div class="opcion">
                    <i class="material-icons">store</i>
                    <span>Zona de Adopcion</span>
                </div>
            </a>
            <a href="../php/cliente/cerrarSesion.php">
                <div class="opcion">
                    <i class="material-icons">logout</i>
                    <span>Cerrar Sesión</span>
                </div>
            </a>
        </div>
    <h1>Historial de Adopcion</h1>
</header>
<div class="contenedorH">
    <table class='tabla'>
        <thead >
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Perro</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql="select ad.id as id , p.id as perroId, p.nombre as nombrePerro,a.nombre as nombreAdoptante,ad.estado as estado, ad.fecha_adopcion as fecha from adopcion ad INNER join perro p on p.id=ad.perro_id INNER join adoptante a on a.id=ad.adoptante_id where a.id=?";
            if ($stmt = mysqli_prepare($conection, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $_SESSION["idCliente"]);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($mostrar = mysqli_fetch_assoc($result)) {
                    if ($mostrar["estado"] !== 'Adoptado') {
                        $boton = "<button class='btndanger cancelarAdopcion' data-id='" . htmlspecialchars($mostrar['perroId']) . "'>Cancelar</button>";
                    } else {
                        $boton = "";
                    }
                    echo "<tr>
                            <td>" . htmlspecialchars($mostrar["id"]) . "</td>
                            <td>" . htmlspecialchars($mostrar["nombreAdoptante"]) . "</td>
                            <td>" . htmlspecialchars($mostrar["nombrePerro"]) . "</td>
                            <td>" . htmlspecialchars($mostrar["fecha"]) . "</td>
                            <td>" . htmlspecialchars($mostrar["estado"]) . "</td>
                            <td>" .$boton ."</td>
                            </tr>";
                }
                mysqli_free_result($result);
            }
            ?>
        </tbody>
    </table>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/script.js?v=<?php echo time();?>"></script>
</body>
</html>