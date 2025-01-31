<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dar de alta</title>
    <style>
    /* Estilo general para la página */
    body {
        font-family: Arial, sans-serif;
        background-color: white;
        color: black;
        margin: 0;
        padding: 0;
    }

    /* Estilo para el navbar centrado */
    nav {
        background-color: black;
        overflow: hidden;
        text-align: center; /* Centra el navbar */
        padding: 10px 0;
    }
    nav a {
        display: inline-block;
        color: white;
        text-align: center;
        padding: 14px 20px;
        text-decoration: none;
        font-size: 1.1em;
    }
    nav a:hover {
        background-color: #333;
        color: white;
    }

    /* Estilo para los encabezados */
    h1, h2 {
        color: black;
        text-align: center;
        margin-top: 20px;
    }

    h1 {
        font-size: 2.5em;
    }

    h2 {
        font-size: 1.8em;
    }

    /* Estilo para los formularios */
    form {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f4f4f4;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    label {
        display: block;
        margin-bottom: 10px;
        font-size: 1.1em;
    }
    input[type="text"], input[type="email"], input[type="number"], select {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
    }
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: black;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 1.2em;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #333;
    }

    /* Estilo para los mensajes de error y éxito */
    .mensaje {
        text-align: center;
        font-size: 1.2em;
        margin-top: 20px;
    }
    .error {
        color: red;
    }
    .exito {
        color: green;
    }

    /* Estilo para los checkboxes */
    label input[type="checkbox"] {
        margin-right: 10px;
    }
    </style>
</head>
<body>
    <!-- Aqui pongo el mismo nav que había puesto en la pagina pricnipal index -->
<nav >
    <a href="index.php">Index</a>
    <a href="alta.php">Dar de Alta</a>
    <a href="modificar.php">Modificar/Eliminar</a>
</nav>
    <!-- Aqui creo el formulario con el que la personas se podran dar de alta -->
<form action="alta.php" method="post">
<label for="nombre">Nombre Completo</label>
<input type="text" name="nombre">
<label for="correo">Correo electrónico</label>
<input type="email" name="correo">
<label for="edad">Edad</label>
<input type="number" name="edad">
<label for="plan">Tipo de plan</label> <!-- Aqui pongo un select para que el usuario pueda elegir el tipo de plan -->
<select name="plan">
    <option value="basico">Básico</option>
    <option value="estandar">Estándar</option>
    <option value="premium">Premium</option>
</select><br>

<label> 
    <!-- Aqui pongo un checkbox para que la persona puead elegir los paquetes que quiera y estos se guardaran enun array -->
    <input type="checkbox" name="paquete[]" value="deporte"> Paquete Deporte
  </label><br>
  <label>
    <input type="checkbox" name="paquete[]" value="cine"> Paquete Cine
  </label><br>
  <label>
    <input type="checkbox" name="paquete[]" value="infantil"> Paquete Infantil
  </label><br>

<label for="plan">Duración</label> <!-- Aqui pongo un select para que el usuario pueda elegir la duración del plan -->
<select name="duracion">
    <option value="mensual">Mensual</option>
    <option value="anual">Anual</option>
</select>

<input type="submit" value="Confirmar"></form> <!-- Aqui pongo un submit para que el usuario pueda enviar el formulario -->

<?php
// Nos conectamos con el servidor de base de datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mysql = new mysqli("127.0.0.1", "root", "campusfp", "hito1_progr");
 { // Verificamos si se han enviado los valores a través del método POST si existe lo asigna a las variables y si no le asigna null.
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $correo = isset($_POST['correo']) ? $_POST['correo'] : null;
    $edad = isset($_POST['edad']) ? (int)$_POST['edad'] : null;
    $plan = isset($_POST['plan']) ? $_POST['plan'] : null;
    $paquete = isset($_POST['paquete']) ? $_POST['paquete'] : []; // El o los paquetes se almacena en el array $paquete si no se asigna un array vacío.
    $duracion = isset($_POST['duracion']) ? $_POST['duracion'] : null;}

    // Creamos un array para los precios del el tipo de plan
    $precios_plan = [
        'basico' => 9.99,
        'estandar' => 13.99,
        'premium' => 17.99
    ];
    
    // Creamos un array con los precios para los paquetes adicionales
    $precios_paquete = [
        'deporte' => 6.99,
        'cine' => 7.99,
        'infantil' => 4.99
    ];
    
    $precio_total = $precios_plan[$plan]; // Precio base del plan seleccionado

// Sumamos el precio de los paquetes adicionales seleccionados
if (isset($paquete)) { // se comprueba si el array $paquete no está vacío
    foreach ($paquete as $p) {  // se recorre el array $paquete 
        if (isset($precios_paquete[$p])) { // se comprueba si el paquete seleccionado existe en el array $precios_paquete
            $precio_total += $precios_paquete[$p]; // se suma el precio del paquete seleccionado al precio total
        }
    }
}
 // Verificamos si el correo ya está registrado
 $result = $mysql->query("SELECT COUNT(*) FROM usuarios WHERE correo = '$correo'");
 $row = $result->fetch_row(); // 
 $count = $row[0];

 if ($count > 0) {
     // Si el correo ya existe, mostramos un mensaje de error
     echo "<h2 class='error'>El correo electrónico $correo ya está registrado.</h2>";
 } else {
     // Creamos las restricciones de la web
     if ($edad < 18 && (in_array('deporte', $paquete) || in_array('cine', $paquete))) { // Si el usuario es menor de edad y ha seleccionado deporte o cine saldra error
         echo "Si eres menor de 18 años no puedes elegir paquetes de deporte o cine.<br>"; 
     } elseif ($plan == 'basico' && count($paquete) > 1) { // Si el usuario selecciona el plan basico y selecciona mas de un paquete saldra error
         echo "Los usuarios del Plan Básico solo pueden seleccionar un paquete adicional.<br>";
     } elseif (in_array('deporte', $paquete) && $duracion != 'anual') { // Si el usuario selecciona deporte y no selecciona anual saldra error
         echo "El Pack Deporte solo puede ser contratado si la duración de la suscripción es de 1 año.<br>";
     } else {
         // Si no se cumplen las condiciones anteriores se va a registrar al usuario en la base de datos.
         $paquete_str = implode(",", $paquete);
         // Inserta los datos del usuario en la base de datos.
         $insert = $mysql->query("INSERT INTO usuarios (nombre, correo, edad, plan, paquete, duracion, precio) 
                                  VALUES ('$nombre', '$correo', '$edad', '$plan', '$paquete_str', '$duracion', $precio_total)");
         // Verifica si la inserción en la base de datos fue exitosa.
         if($insert){
             echo "<h2>Se ha dado de alta el usuario $nombre y tendrá un costo total de $precio_total</h2>";
         } else {
             echo "<h2>Error, no se ha dado de alta el usuario $nombre</h2>";
         }
         $mysql->close(); // Cerramos la conexión con la base de datos.
     }
 }
}
?>
    
    </body>
    </html>