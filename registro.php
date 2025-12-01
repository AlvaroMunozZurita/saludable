<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Datos de conexión
$host = "localhost";
$usuario = "root";        // tu usuario de MySQL
$password = "alvaro";     // tu contraseña de MySQL (ajústala si es distinta)
$basededatos = "mealplanner";

// Conexión a la base de datos
$conn = new mysqli($host, $usuario, $password, $basededatos);
if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión a la BD: " . $conn->connect_error]));
}

// Recibir datos JSON del frontend
$data = json_decode(file_get_contents("php://input"), true);

$nombre     = $data["nombre"];
$apellido   = $data["apellido"];
$correo     = $data["correo"];
$usuario    = $data["usuario"];
$contrasena = password_hash($data["contrasena"], PASSWORD_BCRYPT);

// Insertar en la tabla
$sql = "INSERT INTO usuarios (nombre, apellido, correo, usuario, contrasena) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nombre, $apellido, $correo, $usuario, $contrasena);

if ($stmt->execute()) {
    echo json_encode(["message" => "Usuario registrado correctamente"]);
} else {
    echo json_encode(["error" => "Error al registrar usuario: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>