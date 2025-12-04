<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$host = "localhost";
$usuario = "root";
$password = "alvaro";
$basededatos = "mealplanner";

$conn = new mysqli($host, $usuario, $password, $basededatos);

if ($conn->connect_error) {
    die(json_encode(["error" => "Error al conectar: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);

$nombre     = $data["nombre"];
$apellido   = $data["apellido"];
$correo     = $data["correo"];
$usuario    = $data["usuario"];
$contrasena = password_hash($data["contrasena"], PASSWORD_BCRYPT);

$sql = "INSERT INTO usuarios (nombre, apellido, correo, usuario, contrasena) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nombre, $apellido, $correo, $usuario, $contrasena);

if ($stmt->execute()) {
    echo json_encode(["message" => "Usuario registrado correctamente"]);
} else {
    echo json_encode(["error" => "Error al registrar: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
