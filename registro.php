<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

// DATOS DE CONEXIÓN
$host = "localhost";
$usuario_db = "root";     // Renombrado para evitar conflicto con $usuario del formulario
$password = "alvaro";     // ✅ Tu contraseña real
$basededatos = "mealplanner";

// CONEXIÓN
$conn = new mysqli($host, $usuario_db, $password, $basededatos);

if ($conn->connect_error) {
    echo json_encode(["error" => "Error al conectar: " . $conn->connect_error]);
    exit;
}

// LEER JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["error" => "No se recibieron datos"]);
    exit;
}

// VALIDAR QUE EXISTAN LOS CAMPOS
if (
    !isset($data["nombre"]) ||
    !isset($data["apellido"]) ||
    !isset($data["correo"]) ||
    !isset($data["usuario"]) ||
    !isset($data["contrasena"])
) {
    echo json_encode(["error" => "Faltan datos"]);
    exit;
}

// ASIGNAR VARIABLES
$nombre     = $data["nombre"];
$apellido   = $data["apellido"];
$correo     = $data["correo"];
$usuario    = $data["usuario"];
$contrasena = password_hash($data["contrasena"], PASSWORD_BCRYPT);

// INSERTAR
$sql = "INSERT INTO usuarios (nombre, apellido, correo, usuario, contrasena) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nombre, $apellido, $correo, $usuario, $contrasena);

if ($stmt->execute()) {
    echo json_encode(["message" => "✅ Usuario registrado correctamente"]);
} else {
    echo json_encode(["error" => "❌ Error al registrar: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
