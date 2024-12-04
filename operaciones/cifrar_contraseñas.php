<?php
// Configuración de la base de datos
$host = 'localhost';  // Cambia esto si tu base de datos está en otro host
$dbname = 'bibli';    // Nombre de la base de datos
$username = 'root';   // Usuario de la base de datos
$password = '';       // Contraseña de la base de datos

try {
    // Conexión a la base de datos con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Seleccionar todos los usuarios
    $query = "SELECT id_usuario, contraseña FROM Usuario";
    $stmt = $pdo->query($query);

    // Recorrer los resultados
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_usuario = $row['id_usuario'];
        $contraseña_actual = $row['contraseña'];

        // Verificar si la contraseña ya está cifrada
        if (!password_get_info($contraseña_actual)['algo']) {
            // Cifrar la contraseña si no está cifrada
            $contraseña_cifrada = password_hash($contraseña_actual, PASSWORD_DEFAULT);

            // Actualizar la contraseña en la base de datos
            $updateQuery = "UPDATE Usuario SET contraseña = :contraseña WHERE id_usuario = :id_usuario";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':contraseña', $contraseña_cifrada);
            $updateStmt->bindParam(':id_usuario', $id_usuario);
            $updateStmt->execute();

            echo "Contraseña del usuario con ID $id_usuario actualizada.<br>";
        } else {
            echo "Contraseña del usuario con ID $id_usuario ya estaba cifrada.<br>";
        }
    }

    echo "Proceso completado.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
