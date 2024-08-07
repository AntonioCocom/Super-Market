<?php
require_once '../../../controllers/UserController.php';

$_id = $_GET['_id'] ?? '';

try {
    $userController = new UserController();
    $result = $userController->deleteUser($_id);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Cliente eliminado exitosamente']);
    } else {
        http_response_code(500); // Código de estado HTTP 500 Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el cliente']);
    }
} catch (Exception $e) {
    // Manejo de excepciones
    http_response_code(500); // Código de estado HTTP 500 Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
