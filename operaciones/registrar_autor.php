<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bibli"; // Cambia este nombre según tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar el formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre_autor'];
    $apellido = $_POST['apellido_autor'];

    // Validar datos
    if (!empty($nombre) && !empty($apellido)) {
        // Insertar datos en la base de datos
        $sql = "INSERT INTO Autor (nombre, apellido) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $apellido);

        if ($stmt->execute()) {
            echo "Autor registrado exitosamente.";
        } else {
            echo "Error al registrar el autor: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, complete los campos obligatorios.";
    }
}

$conn->close();
?>

