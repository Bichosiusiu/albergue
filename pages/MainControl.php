<?php
session_name('control_sesion');
session_start(); // se inicia sesion
include "../includes/conexion.php";
if (!isset($_SESSION["control"])) { //si no hay nada en en sesion usuario quiere decir que se quiere iniciar sesion desde fuera del login
    echo "<script>
    alert('Por favor, debes iniciar sesión');
    window.location='index.html';
    </script>";
    session_destroy(); // se prohibe el acceso y se destruye la sesión
    die(); //no se ejecuta la página y te devuelve a index.html
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
    <title>MainControl</title>
</head>

<body class="login">
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
        <a href="albergue.php">
            <div class="opcion">
                <i class="material-icons">pets</i>
                <span>Perros</span>
            </div>
        </a>
        <a href="../php/control/cerrarSesionControl.php">
            <div class="opcion">
                <i class="material-icons">logout</i>
                <span>Cerrar Sesión</span>
            </div>
        </a>
    </div>
    <form id="busqadop" name="busqprod" method="POST">
    <div class="busq">
          <input type="number" id="bus" name="bus" required="true" min="1"/>
          <input type="submit" value="Buscar Adopcion" />
    </div>
    </form>
    <h1>Gestión de Adopcion</h1>
</header>
    
<br><br>
<div class="contenedorProd">
    <div class="tabla-scroll">
        <table class='tabla'>
            <tr>
                <th>ID</th>
                <th>Perro</th>
                <th>Edad</th>
                <th>Fecha de Adopcion</th>
                <th>Adoptante</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php
            $sql = "SELECT ad.id as id, p.nombre as nombre, p.edad as edad, ad.fecha_adopcion as fecha, a.nombre as adoptante, ad.estado as estado FROM adopcion ad inner join perro p on p.id= ad.perro_id inner join adoptante a on a.id=ad.adoptante_id where p.albergue_id=?";
            if ($stmt = mysqli_prepare($conection, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $_SESSION["idcontrol"]);
                mysqli_stmt_execute($stmt);
            }
            $result = mysqli_stmt_get_result($stmt);
            while ( $mostrar = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $mostrar["id"] ?></td>
                    <td><?php echo $mostrar["nombre"] ?></td>
                    <td><?php echo $mostrar["edad"] ?></td>
                    <td><?php echo $mostrar["fecha"] ?></td>
                    <td><?php echo $mostrar["adoptante"] ?></td>
                    <td><?php echo $mostrar["estado"] ?></td>
                    <td>
                        <?php if ($mostrar["estado"] !== "Adoptado") { ?>
                        <button class='btnact adoptado' data-id="<?php echo $mostrar["id"]; ?>">Adoptado</button>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/script.js?v=<?php echo time(); ?>"></script>
</body>
</html>