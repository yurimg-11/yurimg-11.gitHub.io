<?php
// Incluir archivo de conexión
include('conexion.php');

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Recibir datos del formulario
$id_usuario = $_POST['id_usuario'];
$id_libro = $_POST['id_libro'] ?? null;
$titulo_libro = $_POST['titulo_libro'] ?? null;  // Título del libro
$fecha_prestamo = $_POST['fecha_prestamo'];

// Verificar si el usuario existe
$sql_verificar_usuario = "SELECT COUNT(*) AS count FROM Usuario WHERE id_usuario = ?";
$stmt_verificar_usuario = $conexion->prepare($sql_verificar_usuario);
$stmt_verificar_usuario->bind_param("i", $id_usuario);
$stmt_verificar_usuario->execute();
$resultado_usuario = $stmt_verificar_usuario->get_result();
$fila_usuario = $resultado_usuario->fetch_assoc();

if ($fila_usuario['count'] == 0) {
    die("Error: El ID de usuario no existe.");
}
$stmt_verificar_usuario->close();

// Si el id_libro no fue proporcionado, buscar el libro por nombre (titulo)
if (!$id_libro && $titulo_libro) {
    $sql_buscar_libro = "SELECT id_libro, Titulo FROM Libro WHERE Titulo = ?";  // Buscar por el título
    $stmt_buscar_libro = $conexion->prepare($sql_buscar_libro);
    $stmt_buscar_libro->bind_param("s", $titulo_libro);
    $stmt_buscar_libro->execute();
    $resultado_libro = $stmt_buscar_libro->get_result();
    $fila_libro = $resultado_libro->fetch_assoc();

    if ($fila_libro) {
        $id_libro = $fila_libro['id_libro'];  // Guardamos el id_libro obtenido
        $titulo_libro = $fila_libro['Titulo']; // Aseguramos el título
    } else {
        die("Error: El libro con el título proporcionado no existe.");
    }
    $stmt_buscar_libro->close();
}

// Si solo se proporciona el id_libro, obtener el título del libro
if ($id_libro && !$titulo_libro) {
    $sql_obtener_titulo = "SELECT Titulo FROM Libro WHERE id_libro = ?";
    $stmt_obtener_titulo = $conexion->prepare($sql_obtener_titulo);
    $stmt_obtener_titulo->bind_param("i", $id_libro);
    $stmt_obtener_titulo->execute();
    $resultado_titulo = $stmt_obtener_titulo->get_result();
    $fila_titulo = $resultado_titulo->fetch_assoc();

    if ($fila_titulo) {
        $titulo_libro = $fila_titulo['Titulo']; // Recuperamos el título
    } else {
        die("Error: No se encontró el título para el ID de libro proporcionado.");
    }
    $stmt_obtener_titulo->close();
}

// Validar nuevamente que se tiene el id_libro y el título
if (!$id_libro || !$titulo_libro) {
    die("Error: No se ha proporcionado información válida del libro.");
}

// Insertar el préstamo en la base de datos
$sql = "INSERT INTO Prestamo (id_usuario, id_libro, Titulo, fecha_prestamo) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iiss", $id_usuario, $id_libro, $titulo_libro, $fecha_prestamo);

if ($stmt->execute()) {
    echo "Préstamo registrado con éxito.";
} else {
    echo "Error al registrar el préstamo: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
