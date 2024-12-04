<?php
// Configuración de la base de datos (usando PDO)
$host = 'localhost';  // Cambia esto si tu base de datos está en otro host
$dbname = 'bibli';  // Nombre de la base de datos
$username = 'root';  // Nombre de usuario de la base de datos
$password = '';  // Contraseña de la base de datos

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}

// Manejar las acciones
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'buscar':
        buscarDevolucion($pdo);
        break;
    
    case 'registrar':
        registrarDevolucion($pdo);
        break;

    default:
        echo "Acción no válida.";
}

function registrarDevolucion($pdo) {
    if (isset($_POST['id_devolucion']) && isset($_POST['id_prestamo']) && isset($_POST['fecha_devolucion'])) {
        $id_devolucion = $_POST['id_devolucion'];
        $id_prestamo = $_POST['id_prestamo'];
        $fecha_devolucion = $_POST['fecha_devolucion'];

        // Validar y sanitizar datos con PDO
        $id_devolucion = $pdo->quote($id_devolucion);  // Sanitización de entrada
        $id_prestamo = $pdo->quote($id_prestamo);
        $fecha_devolucion = $pdo->quote($fecha_devolucion);

        // Verificar si el préstamo existe
        $stmt = $pdo->prepare("SELECT * FROM Prestamo WHERE id_prestamo = :id_prestamo");
        $stmt->bindParam(':id_prestamo', $id_prestamo, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Inserción en la base de datos
            $sql = "INSERT INTO Devolucion (id_prestamo, fecha_devolucion) VALUES ($id_prestamo, $fecha_devolucion)";
            $pdo->exec($sql);
            echo "<div class='alert alert-success'>Devolución registrada con éxito.</div>";
        } else {
            echo "<div class='alert alert-danger'>El préstamo con ID $id_prestamo no existe.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Faltan datos en el formulario.</div>";
    }
}

function buscarDevolucion($pdo) {
    if (isset($_GET['id_devolucion'])) {
        $id_devolucion = $_GET['id_devolucion'];

        // Sanitizar la entrada
        $id_devolucion = $pdo->quote($id_devolucion);

        // Consulta para buscar la devolución
        $stmt = $pdo->prepare("SELECT * FROM Devolucion WHERE id_devolucion = :id_devolucion");
        $stmt->bindParam(':id_devolucion', $id_devolucion, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $devolucion = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<div class='alert alert-info'>";
            echo "<p><strong>ID Devolución:</strong> " . $devolucion['id_devolucion'] . "</p>";
            echo "<p><strong>ID Prestamo:</strong> " . $devolucion['id_prestamo'] . "</p>";
            echo "<p><strong>Fecha Devolución:</strong> " . $devolucion['fecha_devolucion'] . "</p>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-warning'>No se encontró ninguna devolución con ese ID.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Por favor ingresa un ID de devolución para buscar.</div>";
    }
}
?>
