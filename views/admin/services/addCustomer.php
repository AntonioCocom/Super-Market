<?php
require_once '../../../controllers/UserController.php';

// Obtener datos de la solicitud (por ejemplo, desde JSON en el cuerpo de la solicitud)
$data = json_decode(file_get_contents('php://input'), true);
// Verificar que se recibieron los datos necesarios
if (
    !isset($data['first_name']) || $data['first_name'] ==='' ||
    !isset($data['last_name']) || $data['last_name'] ==='' ||
    !isset($data['mobile']) || $data['mobile'] ==='' ||
    !isset($data['email']) || $data['email'] ==='' ||
    !isset($data['address']) || $data['address'] ==='' ||
    !isset($data['state']) || $data['state'] ==='' ||
    !isset($data['town']) || $data['town'] ==='' ||
    !isset($data['points']) || $data['points'] ==='' ||
    !isset($data['password']) || $data['password'] ===''
) {
    echo json_encode(['success' => false, 'message' => 'Datos insuficientes']);
    exit;
}

$userController = new UserController();
$result = $userController->createUser($data);

echo json_encode($result);
exit;
?>
