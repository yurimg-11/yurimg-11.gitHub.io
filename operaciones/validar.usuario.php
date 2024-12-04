<?php
// Configuración de la base de datos
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

// Obtener los datos del formulario
$correo = isset($_POST['correo']) ? $_POST['correo'] : '';
$contraseña = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';

// Comprobar si los datos están completos
if (empty($correo) || empty($contraseña)) {
    echo json_encode(['success' => false, 'message' => 'Correo o contraseña vacíos.']);
    exit();
}

// Consulta SQL para obtener el usuario
$query = "SELECT * FROM Usuario WHERE correo = :correo LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':correo', $correo);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el usuario existe
if ($user) {
    // Verificar la contraseña
    if (password_verify($contraseña, $user['contraseña'])) {
        // La contraseña es correcta
        echo json_encode(['success' => true]);
    } else {
        // La contraseña es incorrecta
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
    }
} else {
    // El correo no está registrado
    echo json_encode(['success' => false, 'message' => 'Correo no registrado.']);
}
?>
