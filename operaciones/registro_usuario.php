<?php
include 'conexion.php'; // Incluye tu archivo de conexión

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Verificar si se enviaron datos mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $contraseña = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : '';

    // Validar que no estén vacíos
    if (empty($correo) || empty($contraseña)) {
        echo "Por favor, completa todos los campos.";
        exit();
    }

    // Verificar si el correo ya está registrado
    $query = $conexion->prepare("SELECT id_usuario FROM Usuario WHERE correo = ?");
    $query->bind_param("s", $correo);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
        echo "El correo ya está registrado. Intenta con otro.";
        exit();
    }

    $query->close();

    // Cifrar la contraseña
    $contraseña_cifrada = password_hash($contraseña, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario
    $insert_query = $conexion->prepare("INSERT INTO Usuario (correo, contraseña) VALUES (?, ?)");
    $insert_query->bind_param("ss", $correo, $contraseña_cifrada);

    if ($insert_query->execute()) {
        echo "Usuario registrado exitosamente.";
    } else {
        echo "Error al registrar al usuario: " . $insert_query->error;
    }

    $insert_query->close();
}

$conexion->close();
?>
