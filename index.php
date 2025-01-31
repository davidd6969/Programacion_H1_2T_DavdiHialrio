<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreamWeb DavidHL</title>
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

    /* Estilo para las tablas */
    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        color: black;
    }
    th, td {
        padding: 12px;
        text-align: left;
        border: 1px solid black;
    }
    th {
        background-color: #333;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f4f4f4;
    }
    tr:hover {
        background-color: #ddd;
    }

    /* Estilo para el formulario */
    form {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    input[type="email"] {
        padding: 10px;
        width: 250px;
        margin-right: 10px;
        border: 1px solid black;
        border-radius: 4px;
        background-color: #f4f4f4;
        color: black;
    }
    button {
        padding: 10px 20px;
        background-color: black;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    button:hover {
        background-color: #333;
    }

    /* Estilo para los detalles del usuario */
    .usuario-detalles {
        background-color: #f4f4f4;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .usuario-detalles p {
        font-size: 1.1em;
        margin: 5px 0;
    }
    .usuario-detalles strong {
        color: black;
    }
    </style>
</head>
<body>
    <nav> <!-- Aqui he creado el nav que aparecera en todas la paginas y te redigirá a ellas -->
        <a href="index.php">Index</a>
        <a href="alta.php">Dar de Alta</a>
        <a href="modificar.php">Modificar/Eliminar</a>
    </nav>
    <h1>USUARIOS DADOS DE ALTA</h1>
    <?php
    $mysql = mysqli_connect("127.0.0.1", "root", "campusfp","hito1_progr");  // Conexión a la base de datos
    $sql = "SELECT * FROM usuarios";  // Consulta SQL para seleccionar todos los usuarios
    $resultado = $mysql->query($sql);
    echo "<table>";  // Creo una tabla para mostrar los datos de los usuarios con los datos que haya en la base de datos
    echo "<tr><th>NOMBRE COMPLETO</th><th>CORREO</th><th>EDAD</th><th>PLAN</th><th>PAQUETE</th><th>DURACÍON</th><th>PRECIO</th></tr>";
     // Verificamos si la consulta devuelve resultados sobre los datos de los usuarios
    if ($resultado->num_rows > 0) { 
        while ($fila = $resultado->fetch_assoc()) { // Recorre los resultados y los muestra en la tabla
            echo "<tr>
                <td>{$fila['nombre']}</td>
                <td>{$fila['correo']}</td>
                <td>{$fila['edad']}</td>
                <td>{$fila['plan']}</td>
                <td>{$fila['paquete']}</td>
                <td>{$fila['duracion']}</td>
                <td>{$fila['precio']}</td>
            </tr>";
        }
    } // Cierra la conexión a la base de datos
    $mysql->close();
    ?>
    </table> 
    <!-- Creo una tabla con los precios de todos los planes -->
    <h2>Precios de Planes</h2>
    <table>
        <tr>
            <th>Tipo de plan</th>
            <th>Precio Mensual (€)</th>
        </tr>
        <tr>
            <td>Plan Básico (1 dispositivo)</td>
            <td>9,99 €</td>
        </tr>
        <tr>
            <td>Plan Estándar (2 dispositivos)</td>
            <td>13,99 €</td>
        </tr>
        <tr>
            <td>Plan Premium (4 dispositivos)</td>
            <td>17,99 €</td>
        </tr>
    </table>
<!-- Creo una tabla con los precios de todos los paquetes -->
    <h2>Precios de Paquetes</h2>
    <table>
        <tr>
            <th>Pack</th>
            <th>Precio Mensual (€)</th>
        </tr>
        <tr>
            <td>Deporte</td>
            <td>6,99 €</td>
        </tr>
        <tr>
            <td>Cine</td>
            <td>7,99 €</td>
        </tr>
        <tr>
            <td>Infantil</td>
            <td>4,99 €</td>
        </tr>
    </table>
<!-- Creo unformulario para buscar por correo y que nos muestre el precio desglosado -->
    <h2>Detalles del usuario</h2>
    <form method="POST">
        <label for="correo_buscar">Correo del usuario:</label>
        <input type="email" name="correo_buscar" required>
        <button type="submit">Buscar</button>
    </form>

    <?php
    // Verificamos si el formulario fue enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['correo_buscar'])) {
        $correo_buscar = $_POST['correo_buscar'];
        // Conexión a la base de datos
        $mysql = mysqli_connect("127.0.0.1", "root", "campusfp", "hito1_progr");
        // Consulta SQL para buscar el usuario por correo
        $sql = "SELECT * FROM usuarios WHERE correo = '$correo_buscar'";
        $resultado = $mysql->query($sql);
        // se verifica si se ha encontrado un usuario con ese correo
        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            
            $plan = strtolower($fila['plan']); // Convertimos a minúsculas para evitar conflictos
            $paquete_str = $fila['paquete'];
            $duracion = $fila['duracion'];
            // definimos un array con los precios de los planes
            $precios_plan = [
                'básico' => 9.99,
                'estándar' => 13.99,
                'premium' => 17.99
            ];
            // definimos un array con los precios de los paquetes
            $precios_paquete = [
                'deporte' => 6.99,
                'cine' => 7.99,
                'infantil' => 4.99
            ];
            // calculamos el precio
            $precio_total = $precios_plan[$plan] ?? 0;
            // Divide los paquetes en una lista y calcula el precio total de los paquetes
            $paquetes = explode(",", $paquete_str);
            $precio_paquetes = 0;
            
            foreach ($paquetes as $p) {
                if (isset($precios_paquete[$p])) {
                    $precio_paquetes += $precios_paquete[$p];
                }
            }
            // Suma el precio total de los paquetes al precio total del plan
            $precio_total += $precio_paquetes;
            // Muestra los detalles del usuario
            echo "<h3>Detalles del Usuario</h3>";
            echo "<p><strong>Nombre:</strong> {$fila['nombre']}</p>";
            echo "<p><strong>Correo:</strong> {$fila['correo']}</p>";
            echo "<p><strong>Plan:</strong> {$fila['plan']} - {$precios_plan[$plan]} €</p>";
            echo "<p><strong>Duración:</strong> {$fila['duracion']}</p>";
            echo "<p><strong>Paquetes contratados:</strong> {$fila['paquete']}</p>";
            echo "<p><strong>Precio total de paquetes:</strong> {$precio_paquetes} €</p>";
            echo "<h3><strong>Precio Total Mensual:</strong> {$precio_total} €</h3>";
        } else {  // Si no se encuentra el usuario, muestra un mensaje de que no hay usuarios con ese correo
            echo "<p>No se encontró ningún usuario con ese correo.</p>";
        }
        // Cierra la conexión a la base de datos
        $mysql->close();
    }
    ?>
</body>
</html>
