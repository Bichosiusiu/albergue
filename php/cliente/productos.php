<?php
session_name('cliente_sesion');
session_start();
include "../../includes/conexion.php";
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["categoria"])) {
                    $opcionSeleccionada = $_POST["categoria"];
                    $sql = "SELECT * FROM perro WHERE albergue_id='$opcionSeleccionada' and disponible='1'";
                    $sql2 = "SELECT * FROM albergue WHERE id='$opcionSeleccionada'";
                    $rta = mysqli_query($conection, $sql);
                    $rta2 = mysqli_query($conection, $sql2);
                    $mostrar2 = mysqli_fetch_row($rta2);
                    $_SESSION["telAlbergue"] = $mostrar2['3'];
                    $_SESSION["nomAlbergue"] = $mostrar2['1'];
                    $_SESSION["emailAlbergue"] = $mostrar2['4'];
                    $_SESSION["dirAlbergue"] = $mostrar2['2'];
                    while ($mostrar = mysqli_fetch_row($rta)) {
                ?>
                        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
                        <div class="wrapper">
                            <form id="car" method="POST">
                                <div class="img"><img src="<?php echo $mostrar['4']; ?>"></div>
                                <div class="info">
                                    <div class="text">
                                        <h2><?php echo $mostrar['1'] ?></h2>
                                        <h3>Edad: <?php echo $mostrar['2'] ?> años</h3>
                                        <p><?php echo $mostrar['3'] ?></p>
                                    </div>
                                    <div class="posnac">
                                        <div class="caja1"><?php echo $mostrar['0'] ?></div>
                                    </div>
                                    <div class="but-select">
                                        <input type="hidden" name="idPerro" value="<?= $mostrar["0"] ?>">
                                        <input type="hidden" name="namePerro" value="<?= $mostrar["1"] ?>">
                                        <input type="submit" name="add_to_cart" value="Adoptar">
                                    </div>
                                </div>
                            </form>
                        </div>
                <?php
                    }
                }
                ?>