<?php
// Incluir archivo de conexión
include('conexion.php');

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Recibir datos del formulario
$criterio = $_GET['criterio_busqueda'];
$valor = $_GET['valor_busqueda'];

// Construir la consulta SQL
if ($criterio === "id_prestamo") {
    $sql = "SELECT * FROM Prestamo WHERE id_prestamo = ?";
} elseif ($criterio === "nombre_usuario") {
    $sql = "
        SELECT p.id_prestamo, p.fecha_prestamo, u.nombre AS nombre_usuario, l.titulo AS titulo_libro
        FROM Prestamo p
        INNER JOIN Usuario u ON p.id_usuario = u.id_usuario
        INNER JOIN Libro l ON p.id_libro = l.id_libro
        WHERE u.nombre LIKE ?";
    $valor = "%$valor%"; // Para búsqueda parcial
} else {
    die("Criterio de búsqueda no válido.");
}

// Ejecutar la consulta
$stmt = $conexion->prepare($sql);

if ($criterio === "id_prestamo") {
    $stmt->bind_param("i", $valor);
} else {
    $stmt->bind_param("s", $valor);
}

$stmt->execute();
$result = $stmt->get_result();

// Mostrar resultados
if ($result->num_rows > 0) {
    echo "<h2>Resultados de la búsqueda:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID Préstamo</th><th>Usuario</th><th>Libro</th><th>Fecha Préstamo</th><th>Fecha Devolución</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id_prestamo'] . "</td>";
        echo "<td>" . ($criterio === "nombre_usuario" ? $row['nombre_usuario'] : "N/A") . "</td>";
        echo "<td>" . ($criterio === "nombre_usuario" ? $row['titulo_libro'] : "N/A") . "</td>";
        echo "<td>" . $row['fecha_prestamo'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

$stmt->close();
$conexion->close();
?>
