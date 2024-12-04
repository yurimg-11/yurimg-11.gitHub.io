<?php
require 'conexion.php'; // Archivo con la conexión a la base de datos

$accion = $_POST['accion'] ?? $_GET['accion'] ?? ''; // Acción de la solicitud
$mensaje = '';
$editorial = null;

try {
    switch ($accion) {
        case 'registrar':
            // Registrar nueva editorial
            $nombre = trim($_POST['nombre_editorial'] ?? '');
            if (!empty($nombre)) {
                $sql = "INSERT INTO Editorial (nombre) VALUES (?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('s', $nombre);
                if ($stmt->execute()) {
                    $mensaje = "Editorial registrada exitosamente.";
                } else {
                    $mensaje = "Error al registrar la editorial.";
                }
            } else {
                $mensaje = "El nombre de la editorial no puede estar vacío.";
            }
            break;

        case 'buscar':
            // Buscar editorial por ID
            $id = intval($_GET['id_editorial'] ?? 0);
            if ($id > 0) {
                $sql = "SELECT * FROM Editorial WHERE id_editorial = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $editorial = $result->fetch_assoc();
            
                if ($editorial) {
                    $mensaje = "Se encontró exitosamente: ID - " . $editorial['id_editorial'] . ", Nombre - " . $editorial['nombre'];
                } else {
                    $mensaje = "Editorial no encontrada.";
                }
            } else {
                $mensaje = "ID de editorial no válido.";
            }
            break;

        case 'editar':
            // Editar nombre de editorial
            $id = intval($_POST['id_editorial'] ?? 0);
            $nuevoNombre = trim($_POST['nombre_editorial'] ?? '');
            if ($id > 0 && !empty($nuevoNombre)) {
                $sql = "UPDATE Editorial SET nombre = ? WHERE id_editorial = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('si', $nuevoNombre, $id);
                if ($stmt->execute()) {
                    $mensaje = "Editorial actualizada exitosamente.";
                } else {
                    $mensaje = "Error al actualizar la editorial.";
                }
            } else {
                $mensaje = "El nombre y el ID no pueden estar vacíos.";
            }
            break;

        case 'eliminar':
            // Eliminar editorial
            $id = intval($_POST['id_editorial'] ?? 0);
            if ($id > 0) {
                $sql = "DELETE FROM Editorial WHERE id_editorial = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    $mensaje = "Editorial eliminada exitosamente.";
                } else {
                    $mensaje = "Error al eliminar la editorial.";
                }
            } else {
                $mensaje = "ID de editorial no válido.";
            }
            break;

        default:
            $mensaje = "Acción no válida.";
            break;
    }
} catch (Exception $e) {
    $mensaje = "Error en la operación: " . $e->getMessage();
}

// Respuesta para mostrar en el frontend
if ($accion === 'buscar' && $editorial) {
    echo "
        <form action='registrar_editorial.php' method='POST'>
            <input type='hidden' name='accion' value='editar'>
            <input type='hidden' name='id_editorial' value='{$editorial['id_editorial']}'>
            <div class='form-group'>
                <label for='nombre_editorial'>Nombre:</label>
                <input type='text' id='nombre_editorial' name='nombre_editorial' class='form-control' value='{$editorial['nombre']}' required>
            </div>
            <button type='submit' class='btn btn-warning'>Guardar Cambios</button>
        </form>
        <form action='registrar_editorial.php' method='POST' class='mt-2'>
            <input type='hidden' name='accion' value='eliminar'>
            <input type='hidden' name='id_editorial' value='{$editorial['id_editorial']}'>
            <button type='submit' class='btn btn-danger'>Eliminar</button>
        </form>
    ";
} elseif (!empty($mensaje)) {
    echo "<div class='alert alert-info'>$mensaje</div>";
}
?>
