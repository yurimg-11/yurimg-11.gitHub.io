<?php
// Habilitar visualización de errores (útil para depuración)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo de conexión
include 'conexion.php'; // Verifica que la ruta del archivo de conexión sea correcta

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Manejar caso cuando se accede al archivo sin parámetros
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
    echo "<h2>Por favor, utiliza el formulario para buscar libros.</h2>";
    echo "<a href='../buscar.libro.html'>Regresar al formulario</a>"; // Cambia a la ruta del formulario
    exit();
}

// Obtener valores del formulario (valida si existen y evita errores)
$titulo = $_GET['titulo'] ?? '';
$genero = $_GET['genero'] ?? '';
$ano_registro = $_GET['ano_publicacion'] ?? '';
$id_registro = $_GET['id_registro'] ?? '';

// Construir la consulta SQL
$sql = "SELECT L.Titulo, A.Nombre AS NombreAutor, A.Apellido AS ApellidoAutor, G.nombre AS Genero, L.Año_publicacion
        FROM Libro L
        JOIN autor_libro AL ON L.ID_Libro = AL.ID_Libro
        JOIN Autor A ON AL.ID_Autor = A.ID_Autor
        LEFT JOIN Genero G ON L.ID_Genero = G.ID_Genero  -- Corregido aquí: 'L.ID_Genero' en lugar de 'L.Genero_ID'
        WHERE 1=1"; // Base de la consulta

// Agregar condiciones de búsqueda
if (!empty($titulo)) {
    $sql .= " AND L.Titulo LIKE '%" . $conexion->real_escape_string($titulo) . "%'";
}
if (!empty($genero)) {
    $sql .= " AND L.ID_Genero = " . $conexion->real_escape_string($genero);  // Corregido aquí también
}
if (!empty($ano_registro)) {
    $sql .= " AND L.Año_publicacion = " . $conexion->real_escape_string($ano_registro);
}
if (!empty($id_registro)) {
    $sql .= " AND L.ID_Registro LIKE '%" . $conexion->real_escape_string($id_registro) . "%'";
}

// Ejecutar consulta
$resultado = $conexion->query($sql);

// Mostrar resultados
echo "<h1>Resultados de la búsqueda:</h1>";
if ($resultado->num_rows > 0) {
    echo "<ul>";
    while ($fila = $resultado->fetch_assoc()) {
        echo "<li><strong>Título:</strong> " . $fila['Titulo'] . 
             " | <strong>Autor:</strong> " . $fila['NombreAutor'] . " " . $fila['ApellidoAutor'] . 
             " | <strong>Genero:</strong> " . $fila['Genero'] . 
             " | <strong>Año de publicacion:</strong> " . $fila['Año_publicacion'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No se encontraron resultados para los criterios ingresados.</p>";
}

// Cerrar la conexión
$conexion->close();
?>
