<?php
session_name('control_sesion');
session_start();
include '../../includes/conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bus'])) {
        $id = $_POST['bus'];
        $sql = "SELECT ad.id as id, p.nombre as nombre, p.edad as edad, ad.fecha_adopcion as fecha, a.nombre as adoptante, ad.estado as estado FROM adopcion ad inner join perro p on p.id= ad.perro_id inner join adoptante a on a.id=ad.adoptante_id where p.albergue_id=? and ad.id=?";
        if ($stmt = mysqli_prepare($conection, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ii', $_SESSION["idcontrol"], $id);
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    mysqli_stmt_bind_result($stmt, $id, $nombre, $edad, $fecha, $adoptante, $estado);
                    $output = " ";
                    $output .= "<table class='tabla'>";
                    $output .= "<tr>
                                    <th>ID</th>
                                    <th>Perro</th>
                                    <th>Edad</th>
                                    <th>Fecha de Adopcion</th>
                                    <th>Adoptante</th>
                                    <th>Estado</th>
                                    <th>accion</th>
                                </tr>";
                    
                    while (mysqli_stmt_fetch($stmt)) {
                        if ($estado !== 'Adoptado') {
                            $boton = "<button class='btnact adoptado' data-id='" . $id . "'>Adoptado</button>";
                        } else {
                            $boton = "";
                        }
                        $output .= "<tr>
                                        <td>".$id."</td>
                                        <td>".$nombre."</td>
                                        <td>".$edad."</td>
                                        <td>".$fecha."</td>
                                        <td>".$adoptante."</td>
                                        <td>".$estado."</td>
                                        <td>".$boton."</td>
                                    </tr>";
                    }
                    
                    $output .= "</table>";
                    $output .= "</div>";
                    
                    echo $output;
                } else {
                    echo "No se encontraron resultados";
                }
            } else {
                echo "Error al ejecutar la consulta: " . mysqli_stmt_error($stmt);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($conection);
        }
    } else {
        echo "No se recibió el parámetro 'bus' correctamente";
    }
    
    mysqli_close($conection);
}
?>
