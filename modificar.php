<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <style>
        /* Estilo general para la página */
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            color: black;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Estilo para el navbar */
        nav {
            background-color: black;
            width: 100%;
            padding: 10px 0;
            text-align: center;
        }

        nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.1em;
        }

        nav a:hover {
            background-color: #333;
        }

        /* Estilo para los encabezados */
        h1 {
            font-size: 2.5em;
            color: black;
            margin-top: 20px;
            text-align: center;
        }

        /* Estilo para la tabla */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            color: black;
        }

        th, td {
            padding: 10px 15px;
            text-align: left;
            border: 1px solid black;
        }

        th {
            background-color: black;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        tr:hover {
            background-color: #ddd;
        }

        /* Estilo para los formularios */
        form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-top: 20px;
            padding: 20px;
            width: 80%;
            max-width: 500px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select {
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            width: 100%;
            max-width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        button {
            background-color: black;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 1em;
            border-radius: 4px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #333;
        }

        /* Mensajes de error */
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
        }

    </style>
</head>
<body>
<nav>
        <a href="index.php">Index</a>
        <a href="alta.php">Dar de Alta</a>
        <a href="modificar.php">Modificar/Eliminar</a>
    </nav>

<?php
$mysql = mysqli_connect("127.0.0.1", "root", "campusfp", "hito1_progr");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar'])) {
    $correo = $_POST['correo'];
    $sql_delete = "DELETE FROM usuarios WHERE correo = '$correo'";
    if (mysqli_query($mysql, $sql_delete)) {
        echo "Usuario eliminado correctamente";
    } else {
        echo "Error al eliminar el usuario". mysqli_error($mysql);
    }
}
// Comprobamos que el formulario ha sifo enviado para eliminar un usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modificar'])) {
    $correo = $_POST['correo'];
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $plan = $_POST['plan'];
    $paquetes = isset($_POST['paquete']) ? $_POST['paquete'] : [];
    $duracion = $_POST['duracion'] ;

    // definimos las restricciones basadas en la edad
    if ($edad < 18 && !in_array('infantil', $paquetes)) {
        echo "Los usuarios menores de 18 años solo pueden contratar el Pack Infantil";
    } elseif ($plan == 'basico' && count($paquetes) > 1) {
        echo "Los usuarios del Plan Básico solo pueden seleccionar un paquete adicional";
    } elseif (in_array('deporte', $paquetes) && $duracion != 'anual') {
        echo "El Pack Deporte solo puede ser contratado si la duración de la suscripción es de 1 año";
    } else {
        // convertimos los paquetes seleccionados en una cadena para almacenarlos en la base de datos
        $paquetes_selec = implode(',', $paquetes);
        $sql_update = "UPDATE usuarios SET nombre = '$nombre', edad = '$edad', plan = '$plan', paquete = '$paquetes_selec', duracion = '$duracion' WHERE correo = '$correo'";
        if (mysqli_query($mysql, $sql_update)) {
            echo "Usuario modificado correctamente";
        } else {
            echo "Error al modificar el usuario: " . mysqli_error($mysql) . "</div><br>";
        }
    }
}

$sql = "SELECT * FROM usuarios"; // hacemos esta consulta para obtener todos los usuarios
$resultado = mysqli_query($mysql, $sql);

echo "<table>"; // creamos una tabla para mostrar los usuarios
echo "<tr>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Edad</th>
        <th>Plan</th>
        <th>Paquete</th>
        <th>Duración</th>
        <th>Acciones</th>
      </tr>";

if (mysqli_num_rows($resultado) > 0) { // comprobamos si hay usuarios
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $paquetes = explode(',', $fila['paquete']);
        $menor_edad = $fila['edad'] < 18;  // comprobamos si el usuario es menor de edad
        
        /* Mostramos los datos del usuario */
        echo "<tr>
                <td>{$fila['nombre']}</td>
                <td>{$fila['correo']}</td>
                <td>{$fila['edad']}</td>
                <td>{$fila['plan']}</td>
                <td>{$fila['paquete']}</td>
                <td>{$fila['duracion']}</td>
                <td>
                    <form action='' method='POST' style='display:inline;'> 
                    <input type='hidden' name='correo' value='{$fila['correo']}'>
                    <button type='submit' name='eliminar'>Eliminar</button>
                    </form><br><br>
                    
                    <form action='' method='POST'>
                    <input type='hidden' name='correo' value='{$fila['correo']}'>
                    <label for='nombre'>Nombre:</label>
                    <input type='text' name='nombre' value='{$fila['nombre']}' required><br>

                    <label for='edad'>Edad:</label>
                    <input type='number' name='edad' value='{$fila['edad']}' required><br>

                    <label for='plan'>Plan:</label>
                    <select name='plan' required>
                        <option value='premium' " . ($fila['plan'] == 'premium' ? 'selected' : '') . ">Premium</option>
                        <option value='estandar' " . ($fila['plan'] == 'estandar' ? 'selected' : '') . ">Estándar</option>
                        <option value='basico' " . ($fila['plan'] == 'basico' ? 'selected' : '') . ">Básico</option>
                    </select><br>";

                        // Mostrar solo el paquete "Infantil" si el usuario es menor de 18 años
                        echo "<label for='paquete'>Paquete:</label>";
                        $paquetes_list = ['deporte', 'cine', 'infantil'];

                        foreach ($paquetes_list as $paquete) {
                            // Si el usuario es menor de edad, solo podrá ver el paquete infantil
                            if ($menor_edad && $paquete != 'infantil') {
                                continue; // nos saltamos los paquetes no permitidos
                            }

                            $checked = in_array($paquete, $paquetes) ? 'checked' : ''; // comprobamos si el paquete está seleccionado
                            echo "<label>
                                    <input type='checkbox' name='paquete[]' value='$paquete' $checked> $paquete
                                  </label><br>";
                        }

        echo "  <label for='duracion'>Duración:</label>
                <select name='duracion'>
                <option value='mensual' " . ($fila['duracion'] == 'mensual' ? 'selected' : '') . ">Mensual</option>
                <option value='anual' " . ($fila['duracion'] == 'anual' ? 'selected' : '') . ">Anual</option>
                </select><br>

                <button type='submit' name='modificar'>Modificar</button>
              </form>
              </td>
            </tr>";
    }
}

echo "</table>";

mysqli_close($mysql);
?>

</body>
</html>
