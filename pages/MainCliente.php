<?php
session_name('cliente_sesion');
session_start();
include "../includes/conexion.php";
if (!isset($_SESSION["idCliente"])) {
    echo "
    <script>
    alert('Por favor, debes iniciar sesión');
    window.location='index.html';
    </script>";
    session_destroy();
    die();
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/estilos.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <title>MainCliente</title>
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
            <a href="historial.php">
                <div class="opcion">
                    <i class="material-icons">history</i>
                    <span>Historial</span>
                </div>
            </a>
            <a href="../php/cliente/cerrarSesion.php">
                <div class="opcion">
                    <i class="material-icons">logout</i>
                    <span>Cerrar Sesión</span>
                </div>
            </a>
        </div>
        <div class="eleccion">
            <form id="eleccionForm" method="post">
                <label>Selecciona un albergue:</label>
                <select id="opciones" name="categoria">
                    <?php
                    $sql = "SELECT `id`, `nombre`, `email`, `direccion` FROM `albergue`";
                    $rta = mysqli_query($conection, $sql);
                    while ($mostrar = mysqli_fetch_row($rta)) { ?>
                        <option value="<?php echo $mostrar['0'] ?>"><?php echo $mostrar['1'] ?></option>
                    <?php } ?>
                </select>
            </form>
        </div>
    </header>
    <main>
        <br><br><br><br>
        <h2 class="TituloMain">Bienvenido, <?php echo $_SESSION["nomcompleto"]; ?>!</h2>
        <div class="contenedorMain">
            <div id="productosContainer" class="contenedorProductos"></div>
        </div>
        
        <div class="contenedorEleccion ">
            <div class="exit exit4">&times;</div>
            <div id="cartContainer"></div>
        </div>
        
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/script.js?v=<?php echo time(); ?>"></script>
    <script>
        $(document).ready(function(){
            var categoria = $(this).find(":selected").val();
            $.ajax({
                type: 'POST',
                url: '../php/cliente/productos.php',
                data: { categoria: categoria },
                success: function(data) {
                    $('#productosContainer').html(data);
                }
            });
        });
        $(document).on('submit', '#car', function(e) {
                    e.preventDefault();
                    const formData = $(this).serialize();
                    console.log("hola");
                    Swal.fire({
                    title: '¿Estás seguro de querer adoptar este perrito?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, adoptar',
                    cancelButtonText: 'Cancelar',
                    background: '#fff3cd',
                    confirmButtonColor: '#228B22',
                    cancelButtonColor: '#d33',
                    customClass: {
                        popup: 'animated fadeInDown',
                        title: 'font-weight-bold',
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        type: "POST",
                        url: '../php/cliente/add.php',
                        data: formData,
                        xhrFields: {
                        responseType: 'blob'  
                        },
                        success: function(response) {
                            console.log(response);
                            console.log("Desacarga");
                            var link = document.createElement('a');
                            link.href = URL.createObjectURL(response);
                            link.download = 'Cita_Adopcion.pdf'; 
                            link.click();
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'AJAX Error: ' + error, 'error');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
