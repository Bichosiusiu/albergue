<?php
session_name('cliente_sesion');
session_start();

include "../../includes/conexion.php";
require_once '../../vendor/autoload.php';

use Dompdf\Dompdf;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $perritoId = htmlspecialchars($_POST['idPerro']);
    $perritoNombre = htmlspecialchars($_POST['namePerro']);
    $adoptanteId = $_SESSION["idCliente"];
    $adoptanteNombre = $_SESSION["nomcompleto"];
    $direccion = $_SESSION["dirAlbergue"] ;
    $fechaCita = date('Y-m-d H:i:s', strtotime('+3 days'));
    $emailalbergue= $_SESSION["emailAlbergue"] ;
    $emailAdoptante= $_SESSION["email"] ; 
    $cantidad = 0;
    $estado = "En espera";
    mysqli_begin_transaction($conection);
    try {
        $query = "INSERT INTO adopcion (perro_id, adoptante_id, fecha_adopcion, estado) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conection, $query);
        mysqli_stmt_bind_param($stmt, "iiss", $perritoId, $adoptanteId, $fechaCita, $estado);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $query2 = "UPDATE perro SET disponible = ? WHERE id = ?";
        $stmt2 = mysqli_prepare($conection, $query2);
        mysqli_stmt_bind_param($stmt2, "ii", $cantidad, $perritoId);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
        mysqli_commit($conection);

    $html = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .header h1 {
                color: #4CAF50;
            }
            .details {
                margin: 20px 0;
            }
            .details p {
                font-size: 14px;
                margin: 5px 0;
            }
            .footer {
                text-align: center;
                margin-top: 30px;
                font-size: 12px;
                color: #888;
            }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>Cita de Adopción</h1>
            <p>Gracias por darle un hogar a $perritoNombre</p>
        </div>
        <div class='details'>
            <p><strong>Adoptante:</strong> $adoptanteNombre</p>
            <p><strong>ID Adoptante:</strong> $adoptanteId</p>
            <p><strong>Perrito:</strong> $perritoNombre</p>
            <p><strong>Fecha de la Cita:</strong> $fechaCita</p>
            <p><strong>Dirección donde recoger a su perrito:</strong> $direccion</p>
        </div>
        <div class='footer'>
            <p>¡Gracias por adoptar y ayudar a darles una mejor vida a nuestros amigos peludos!</p>
        </div>
    </body>
    </html>
    ";
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $contenido_pdf = $dompdf->output();
    $carpeta_destino = '../../pdf';
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=Cita_Adopcion.pdf");
    if (!is_dir($carpeta_destino)) {
        mkdir($carpeta_destino, 0777, true);
    }
    $fileName = "Cita_Adopcion.pdf";
    $ruta_pdf_guardado = $carpeta_destino . '/' . $fileName;
    if (file_put_contents($ruta_pdf_guardado, $contenido_pdf) === false) {
        echo "Hubo un error al guardar el archivo PDF. Por favor, verifica los permisos de escritura.";
    } else {
        echo $dompdf->output();
        exit();
    } 
    }catch (Exception $e) {
        mysqli_rollback($conection);
        echo "Error: " . $e->getMessage();
    }
}
?>
