<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root"; // Cambiar si el usuario no es root
$password = ""; // Cambiar si tu base de datos tiene contraseña
$dbname = "bibli"; // Nombre de tu base de datos

$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);

    // Insertar el género en la tabla
    $sql = "INSERT INTO Genero (nombre) VALUES ('$nombre')";

    if ($conn->query($sql) === TRUE) {
        echo "Género registrado exitosamente.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();
?>
