<?php

require_once '../controllers/UserController.php';
header('Content-Type: application/json');
$userController = new UserController();
$inputToken = $_POST['token'] ?? '';
try {
    $result = $userController->verifyToken($inputToken);
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al procesar la solicitud']);
}
exit;

?>