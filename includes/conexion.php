<?php
$conection = mysqli_connect("localhost","root","","adoptar");
function sanitizeInput($data, $conection) {
    return mysqli_real_escape_string($conection, trim($data));
}