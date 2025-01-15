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
        <a href="MainControl.php">
            <div class="opcion">
                <i class="material-icons">gite</i>
                <span>Adopcion</span>
            </div>
        </a>
        <a href="../php/control/cerrarSesionControl.php">
            <div class="opcion">
                <i class="material-icons">logout</i>
                <span>Cerrar Sesión</span>
            </div>
        </a>
    </div>
    <form id="busqprod" name="busqprod" method="POST">
    <div class="busq">
          <input type="number" id="bus" name="bus" required="true" min="1"/>
          <input type="submit" value="Buscar Perro" />
    </div>
    </form>
    <button class="anadirProducto">Añadir Perro</button>
    <h1>Gestión del Albergue</h1>
</header>
    
<br><br>
<div class="contenedorProd">
    <div class="tabla-scroll">
        <table class='tabla'>
            <tr>
                <th>ID</th>
                <th>Perro</th>
                <th>Edad</th>
                <th>Descripcion</th>
                <th>Imagen</th>
                <th>Disponible</th>
                <th>Acciones</th>
            </tr>
            <?php
            $sql = "SELECT * FROM perro where albergue_id=?";
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
                    <td><?php echo $mostrar["descripcion"] ?></td>
                    <td><?php echo $mostrar["img"] ?></td>
                    <td><?php echo $mostrar["disponible"] ?></td>
                    <td>
                        <button class='btndanger eliminarProd' data-id="<?php echo $mostrar["id"] ?>">Eliminar</button>
                        <button class='btnact actualizarProducto' data-id="<?php echo $mostrar["id"] ?>">Actualizar</button>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>
   

<div id="updateInventarioModal" class="modal">
  <div class="modal-content">
    <form id="updateInventarioForm"class="updateForm">
    <div class="exit exit2">&times;</div>
    <div class="parte">
    <input type="hidden" name="id" id="id" required>
            <label for="nom">Nombre:</label>
            <input type="text" name="nombre" id="nom" required>
            <label for="edad">Edad:</label>
            <input type="number" name="edad" id="edad" step="1" min="1" required>
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" rows="3" required></textarea>
    </div>
    <div class="parte">
    <label for="pre">Precio:</label>
            <label for="min">Miniatura URL:</label>
    <input type="url" name="miniatura" id="min" required>
            <button type="submit">Actualizar</button>
    </div>
    </form>
  </div>
  </div>
  <div id="addInventarioModal" class="modal">
  <div class="modal-content">
    <form id="addInventarioForm" class="updateForm">
    <div class="exit exit3">&times;</div>
    <div class="parte">
            <label for="nom">Nombre:</label>
            <input type="text" name="nombre" id="nom" required>
            <label for="edad">Edad:</label>
            <input type="number" name="edad" id="pre" step="1" min="1" required>
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" rows="3" required></textarea>
    </div>
    <div class="parte">
    <label for="min">Miniatura URL:</label>
    <input type="url" name="miniatura" id="min" required>
            <button type="submit">Registrar Perro</button>
    </div>
    </form>
  </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/script.js?v=<?php echo time(); ?>"></script>
</body>
</html>